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
    const FILE_DEFAULT_UPSD_CONF = 'default.upsd.conf';
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

    function get_upsd_conf($query, $qoutes)
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPSD_CONF);
            $retval = $file->lookup_value("/^".$query."=+/i");
            if ($qoutes) $retval = preg_replace("/\"/", "", $retval);
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }
    function set_upsd_conf($param, $query, $qoutes)
    {
        clearos_profile(__METHOD__, __LINE__);

        if ($qoutes) $param = '"'.$param.'"';
        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPSD_CONF);
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

    function get_ups_list($ups, $value)
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
                if ($list[$i]['name'] === $ups) $item = $i;
            }
            if (!$i == 0)
            {
                //Explode on first occurence only.
                $var = explode("=", $line, 2);
                $list[$i][trim($var[0])] = preg_replace("/\"/", "", trim($var[1]));
            }
        }

        if (!$ups) {
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
                if (!$supported[3]) $supported[1][$key] = 'Offline';
                self::build_ups_commands_list($i, $key, $ups, $supported[1][$key], 'known');
                $i++;
            }
            //End
            //Begin: Add unkown commands to list.
            if ($supported[3])
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
        if (!$item) {
            return $this->clist;
        } else {
            return $this->clist[$item][$value];
        }
        //End
    }
    
    function build_ups_commands_list($i, $key, $ups, $supported, $edit)
    {
        //FIX: Remove need for $i. Splice/Merge arrays together?
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
            $output = $shell->execute('/usr/bin/upsc', $ups, FALSE);
            $output = TRUE;
        } catch (Engine_Exception $e) {
            $output = FALSE;
            //Throw no errors, Supported column will show 'Offline' for failed connection.
        }
        
        if ($output)
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

    function update_ups_commands_list($ups, $command)
    {
        //FIX: This got crazy, upgrade with search_array.
        $file = new File(clearos_app_base('ups_server').'/packaging/'.self::FILE_DEFAULT_UPS_CONF);
        $lines = $file->get_contents_as_array();
        $start = '/\['.$ups.'\]/';
        $end = '/^\[/';
        $conf = array();
        $conf['basic'] = array();
        $i['count']=0;
        foreach ($lines as $line)
        {
            if (preg_match($start, $line)) {
                $found['start'] = TRUE;
                $found['ups'] = TRUE;
                $conf['basic'][] = $line;
                //FIX: use array count
                $found['splice_start'] = $i['count'];
                continue;
            }
            if ($found['start'])
            {
                if (preg_match('/default\./', $line)) {
                    if (preg_match('/default\.'.$command['command'].'\s*=/', $line)) {
                        $found['default'] = TRUE;
                        if (!empty($command['default'])) $conf['default'][] = 'default.'.$command['command'].'='.$command['default'];
                    } else {
                        $conf['default'][] = $line;
                    }
                    continue;
                } elseif (preg_match('/override\./', $line)) {
                    if (preg_match('/override\.'.$command['command'].'\s*=/', $line)) {
                        $found['override'] = TRUE;
                        if (!empty($command['override'])) $conf['override'][] = 'override.'.$command['command'].'='.$command['override'];
                    } else {
                        $conf['override'][] = $line;
                    }
                    continue;
                } elseif (preg_match('/driver\s*=/', $line)) {
                    $found['driver'] = TRUE;
                    if (!empty($command['driver'])) $conf['basic'][] = 'driver='.$command['driver'];
                    continue;
                } elseif (preg_match('/port\s*=/', $line)) {
                    $found['port'] = TRUE;
                    if (!empty($command['port'])) $conf['basic'][] = 'port='.$command['port'];
                    continue;
                } elseif (preg_match('/sdorder\s*=/', $line)) {
                    $found['sdorder'] = TRUE;
                    if (!empty($command['sdorder'])) $conf['basic'][] = 'sdorder='.$command['sdorder'];
                    continue;
                } elseif (preg_match('/desc\s*=/', $line)) {
                    $found['desc'] = TRUE;
                    if (!empty($command['desc'])) $conf['basic'][] = 'desc="'.$command['desc'].'"';
                    continue;
                } elseif (preg_match('/nolock\s*=/', $line)) {
                    $found['nolock'] = TRUE;
                    if (!empty($command['nolock'])) $conf['basic'][] = 'nolock='.$command['nolock'];
                    continue;
                } elseif (preg_match('/ignorelb\s*=/', $line)) {
                    $found['ignorelb'] = TRUE;
                    if (!empty($command['ignorelb'])) $conf['basic'][] = 'ignorelb='.$command['ignorelb'];
                    continue;
                } elseif (preg_match('/maxstartdelay\s*=/', $line)) {
                    $found['maxstartdelay'] = TRUE;
                    if (!empty($command['maxstartdelay']) && $command['maxstartdelay'] != 45) $conf['basic'][] = 'maxstartdelay='.$command['maxstartdelay'];
                    continue;
                } elseif (preg_match($end, $line)) {
                    $found['start'] = FALSE;
                }
            }
            if (!empty($line)) {
                $conf['output'][] = $line;
                $i['count']++;
            }
        }
        //Add UPS if not found
        if (!$found['ups']) {
            $conf['output'][] = '['.$command['name'].']';
            $conf['output'][] = 'desc='.$command['desc'];
            //$found['splice_start'] = $i['count'];
        }
        //Add default variable if not found.
        if (!$found['default'] && !empty($command['default'])) $conf['default'][] = 'default.'.$command['command'].'='.$command['default'];
        //Add override variable if not found.
        if (!$found['override'] && !empty($command['override'])) $conf['override'][] = 'override.'.$command['command'].'='.$command['override'];
        //Add driver variable if not found
        if (!$found['driver'] && !empty($command['driver'])) $conf['basic'][] = 'driver='.$command['driver'];
        //Add port variable if not found
        if (!$found['port'] && !empty($command['port'])) $conf['basic'][] = 'port='.$command['port'];
        //Add sdorder variable if not found
        if (!$found['sdorder'] && !empty($command['sdorder'])) $conf['basic'][] = 'sdorder='.$command['sdorder'];
        //Add desciption variable if not found
        if (!$found['desc'] && !empty($command['desc'])) $conf['basic'][] = 'desc="'.$command['desc'].'"';
        //Add nolock variable if not found
        if (!$found['nolock'] && !empty($command['nolock'])) $conf['basic'][] = 'nolock='.$command['nolock'];
        //Add ignorelb variable if not found
        if (!$found['ignorelb'] && !empty($command['ignorelb'])) $conf['basic'][] = 'ignorelb='.$command['ignorelb'];
        //Add maxstartdelay variable if not found
        if (!$found['maxstartdelay'] && !empty($command['maxstartdelay']) && $command['maxstartdelay'] != 45) $conf['basic'][] = 'maxstartdelay='.$command['maxstartdelay'];
        //Sort and merge arrays
        sort($conf['basic']);
        sort($conf['default']);
        sort($conf['override']);
        //Delete UPS
        if ($command['command'] != 'delete') {
            $output = array_merge($conf['default'], $conf['override']);
            $output = array_merge($conf['basic'], $output);
        }
        array_splice($conf['output'], $found['splice_start'], 0, $output);
        $file->dump_contents_from_array($conf['output']);
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
            //Note: Delete default and override commands from ups_conf also?
            //$command['default'] = $command;
            //$command['override'] = $command;
            //$this->update_ups_commands_list('\w', $command);
        }
    }
    
    function get_upsd_interfaces($interface, $key)
    {
        //Find all interfaces
        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_DEFAULT_UPSD_CONF);
        $data = $file->get_contents();
        $rows = explode("\n", $data);
        $list[] = array();
        foreach ($rows as $line)
        {
            if (preg_match( "/^\s*\#*LISTEN/", $line ))
            {
                $var = explode(" ", $line);
                $enabled = (preg_match('/^#/', $var[0])) ? FALSE : TRUE;
                $list[] = array('enabled' => $enabled,
                    'ip_old' => $var[1],
                    'ip' => $var[1],
                    'port' => $var[2],
                    'ip_validate' => 'ipv4'
                );
            }
        }

        if (!$interface && !$key) {
            //Return all interfaces
            return $list;
        } elseif ($interface && !$key) {
            //Return 1 interface
            return $list[$interface];
        } elseif ($interface && $key) {
            //Return 1 interface value
            return $list[$interface][$key];
        }
    }
    function set_upsd_interfaces($query)
    {
        $enabled = ($query['enabled']) ? '#' : '';
        $file = new File(clearos_app_base('ups_server').'/packaging/'.self::FILE_DEFAULT_UPSD_CONF);
        $match = $file->replace_lines("/^\s*\#*LISTEN\s".$query['ip_old']."/i", $enabled.'LISTEN '.$query['ip'].' '.$query['port']."\n");
        if (!$match) {
            $file->add_lines_after('LISTEN '.$query['ip'].' '.$query['port']."\n", "/^[^#]/");
        }
    }
    function delete_upsd_interfaces($query)
    {
        $file = new File(clearos_app_base('ups_server').'/packaging/'.self::FILE_DEFAULT_UPSD_CONF);
        $file->delete_lines('/^\s*\#*LISTEN\s'.$query.'/');
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
        
        if (!$item) {
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
