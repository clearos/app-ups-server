<?php
namespace clearos\apps\ups_server;

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

clearos_load_language('ups_server');

// Classes
//--------

use \clearos\apps\base\Daemon as Daemon;
use \clearos\apps\base\File as File;
use \clearos\apps\base\Shell as Shell; 

clearos_load_library('base/Daemon');
clearos_load_library('base/File');
clearos_load_library('base/Shell');

// Exceptions
//-----------

use \Exception as Exception;
use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\File_No_Match_Exception as File_No_Match_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/File_No_Match_Exception');

class Nut extends Daemon
{
    const FILE_DEFAULT_NUT_CONF = 'default.nut.conf';
    const FILE_DEFAULT_UPS_CONF = 'default.ups.conf';
    const FILE_CUSTOM_UPS_CONF = 'custom.ups.conf';
    protected $clist = array();

    function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        parent::__construct('nutd');
    }

    function get_nut_conf($query, $qoutes)
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_NUT_CONF);
            $retval = $file->lookup_value("/^".$query."=+/i");
            if ($qoutes) $retval = preg_replace("/\"/", "", $retval);
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }
    
    function set_nut_conf($param, $query, $qoutes)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($qoutes) $param = '"'.$param.'"';
        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_NUT_CONF);
        $match = $file->replace_lines("/^\s*".$query."/i", $query."=".$param."\n");
        if (! $match) {
            $match = $file->replace_lines("/^#".$query."/i",$query."=".$param."\n");

            if (! $match)
                $file->add_lines_after($query."=".$param."\n", "/^[^#]/");
        }
    }

    function get_ups_conf($query, $qoutes)
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPS_CONF);
            $retval = $file->lookup_value("/^".$query."=+/i");
            if ($qoutes) $retval = preg_replace("/\"/", "", $retval);
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }
    
    function set_ups_conf($param, $query, $qoutes)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($qoutes) $param = '"'.$param.'"';
        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPS_CONF);
        $match = $file->replace_lines("/^\s*".$query."/i", $query."=".$param."\n");
        if (! $match) {
            $match = $file->replace_lines("/^#".$query."/i",$query."=".$param."\n");

            if (! $match)
                $file->add_lines_after($query."=".$param."\n", "/^[^#]/");
        }
    }

    function validate_param($param)
    {
        if (!preg_match("/^[A-Za-z0-9\.\-_]+$/", $param))
            return 'Invalid Parameter';
    }

    function get_ups_list($item, $value)
    {
        //Find all upses
        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPS_CONF);
        $data = $file->get_contents();
        $rows = explode("\n", $data);
        $i=0;
        foreach ($rows as $line)
        {
            if (preg_match( "/^\[/", $line ))
            {
                $i++;
                $list[$i]['name'] = str_replace(array('[',']'),'',$line);
            }
            if (!$i == 0)
            {
                //Explode on first occurence
                $var = explode("=", $line, 2);
                $list[$i][trim($var[0])] = preg_replace("/\"/", "", trim($var[1]));
            }
        }

        if (! $item) {
            return $list;
        } else {
            return $list[$item][$value];
        }
    }

    function get_ups_commands_list($ups, $item, $value)
    {
        // if clist not set...
        if (empty($clist))
        {
            //Return supported UPS commands.
            $supported = self::supported_ups_commands_list($ups);
            //Return known UPS commands.
            $commands = self::known_ups_commands_list();
            //Begin: Add known commands to list.
            $i=0;
            foreach ($commands as $key)
            {
                if ($supported[3]) $supported[1][$key] = 'Offline';
                self::build_ups_commands_list($i, $key, $ups, $supported[1][$key], 'known');
                $i++;
            }
            //End
            //Begin: Add unkown commands to list.
            if (!$supported[3])
            {
                //FIX: Needing to use nested array to check array_diff
                $diff = array_diff($supported[0], $commands);
                foreach ($diff as $key)
                {
                    self::build_ups_commands_list($i, $key, $ups, $supported[1][$key], 'unknown');
                    $i++;
                }
            }
            //End
            //Begin: Add custom commands to list.
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CUSTOM_UPS_CONF);
            $data = $file->get_contents();
            $commands = explode("\n", $data);
            foreach ($commands as $key)
            {
                self::build_ups_commands_list($i, $key, $ups, 'Custom', 'custom');
                $i++;
            }
            //End
        }
        //Begin: Return Results.
        if (! $item) {
            return $this->clist;
        } else {
            return $this->clist[$item][$value];
        }
        //End
    }
    
    function build_ups_commands_list($i, $key, $ups, $supported, $edit)
    {
        $this->clist[$i]['command'] = $key;
        $this->clist[$i]['default'] = self::get_ups_list($ups, 'default.'.$key);
        $this->clist[$i]['override'] = self::get_ups_list($ups, 'override.'.$key);
        $this->clist[$i]['supported'] = $supported;
        $this->clist[$i]['edit'] = $edit;
    }
    function supported_ups_commands_list($ups)
    {
        try {
            $shell = new Shell();
            $output = $shell->execute('/usr/bin/upsc', self::get_ups_list($ups, 'name'), FALSE);
        } catch (Engine_Exception $e) {
            //Throw no errors, Supported column will show 'Offline' for failed connection.
        }
        
        if ($output == 0)
        {
            $rows = $shell->get_output();
            foreach($rows as $key)
            {
                $var = explode(":",$key);
                //FIX: Needing to use nested array to check array_diff
                $commands[0][] = trim($var[0]);
                $commands[1][trim($var[0])] = trim($var[1]);
            }
            $commands[3] = TRUE;
            return $commands;
        }
        $commands[3] = FALSE;
        return $commands;
    }
    function known_ups_commands_list()
    {
        $commands = array
        (
            "",
            //"battery.charge",
            "battery.charge.low",
            "battery.charge.warning",
            "battery.date",
            "battery.mfr.date",
            "battery.runtime",
            "battery.runtime.low",
            "battery.type",
            "battery.voltage",
            "battery.voltage.nominal",
            "device.mfr",
            "device.model",
            "device.serial",
            "device.type",
            "driver.name",
            "driver.parameter.pollfreq",
            "driver.parameter.pollinterval",
            "driver.parameter.port",
            "driver.version",
            "driver.version.data",
            "driver.version.internal",
            "input.sensitivity",
            "input.transfer.high",
            "input.transfer.low",
            "input.transfer.reason",
            "input.voltage",
            "input.voltage.nominal",
            "ups.beeper.status",
            "ups.delay.shutdown",
            "ups.firmware",
            "ups.firmware.aux",
            "ups.load",
            "ups.mfr",
            "ups.mfr.date",
            "ups.model",
            "ups.productid",
            "ups.realpower.nominal",
            "ups.serial",
            "ups.status",
            "ups.test.result",
            "ups.timer.reboot",
            "ups.timer.shutdown",
            "ups.vendorid"
        );
        return $commands;
    }
    
    function update_ups_commands_list($ups, $command, $default, $override)
    {
        $file = new File(clearos_app_base('ups_server').'/packaging/'.self::FILE_DEFAULT_UPS_CONF);
        $file_exit = new File(clearos_app_base('ups_server').'/packaging/exit.conf');
        //match $command after [$ups] and before next [ or EOL
        //if $default or $override = null remove line
        //if match replace else add after.
        $search = 'default.'.$command.'=';
        $replacement = 'default.'.$command.'='.$default;
        $start = "/^\[apc\]/";
        $end = "/\[/";
        $file_exit->add_lines($search."\n");
        $file_exit->add_lines($replacement."\n");
        $file_exit->add_lines($start."\n");
        $file_exit->add_lines($end."\n");
        $match = $file->replace_lines_between($search, $replacement, $start, $end);
        if (! $match) {
            //$match = $file->replace_lines("/^#".$search."/i",$replacement."\n");

            //if (! $match)
                //$file->add_lines_after($replacement."\n", "/^[^#]/");
        }
    }
    
    function set_custom_commands_list($edit, $command)
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(clearos_app_base('ups_server'). "/packaging/custom.ups.conf");
        if ($edit === 'add')
        {
            $file->add_lines_after("$command\n", "/^[^#]/");
        } else {
            $file->delete_lines("/".$command."$/");
        }
    }
    
    function get_upsd_interfaces($item, $value)
    {
        $list[1]['validate'] = 'ipv4';
        $list[1]['ip'] = '192.168.100.100';
        $list[1]['port'] = '3876';
        $list[2]['validate'] = 'ipv4';
        $list[2]['ip'] = '192.168.100.171';
        $list[2]['port'] = '3876';
        $list[3]['validate'] = 'ipv6';
        $list[3]['ip'] = '::1';
        $list[3]['port'] = '3876';
        
        if (! $item) {
            return $list;
        } else {
            return $list[$item][$value];
        }
    }

    function get_users_list($item, $value)
    {
        $list[1]['name'] = 'user1';
        $list[1]['pwd'] = 'password';
        $list[1]['actions_set'] = '1';
        $list[1]['actions_fsd'] = '1';
        $list[1]['upsmon'] = 'master';
        $list[2]['name'] = 'user2';
        $list[2]['upsmon'] = 'slave';
        $list[2]['pwd'] = 'password';
        $list[2]['actions_set'] = '0';
        $list[2]['actions_fsd'] = '0';
        
        if (! $item) {
            return $list;
        } else {
            return $list[$item][$value];
        }
    }
    
    function get_user_commands_list()
    {
        $list[1]['command'] = 'test.panel.start';
        $list[1]['chkd'] = 'TRUE';
        $list[2]['command'] = 'test.panel.stop';
        $list[2]['chkd'] = 'FALSE';
        return $list;
    }
}
