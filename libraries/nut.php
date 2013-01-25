<?php
namespace clearos\apps\ups_server;

$bootstrap = getenv('CLEAROS_BOOTSTRAP') ? getenv('CLEAROS_BOOTSTRAP') : '/usr/clearos/framework/shared';
require_once $bootstrap . '/bootstrap.php';

clearos_load_language('ups_server');

// Classes
//--------

use \clearos\apps\base\Daemon as Daemon;
use \clearos\apps\base\File as File;

clearos_load_library('base/Daemon');
clearos_load_library('base/File');

// Exceptions
//-----------

use \Exception as Exception;
use \clearos\apps\base\Engine_Exception as Engine_Exception;
use \clearos\apps\base\File_No_Match_Exception as File_No_Match_Exception;

clearos_load_library('base/Engine_Exception');
clearos_load_library('base/File_No_Match_Exception');

class Nut extends Daemon
{
    const FILE_CONFIG = 'default.nut.conf';

    function __construct()
    {
        clearos_profile(__METHOD__, __LINE__);

        parent::__construct('nutd');
    }

    function get_server_mode()
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
            $retval = $file->lookup_value("/^MODE\s+/i");
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }

    function set_server_mode($server_mode)
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
        $match = $file->replace_lines("/^\s*MODE/i", "MODE $server_mode\n");

        if (! $match) {
            $match = $file->replace_lines("/^#MODE/i", "MODE $server_mode\n");

            if (! $match)
                $file->add_lines_after("MODE $server_mode\n", "/^[^#]/");
        }
    }
    
    function validate_server_mode($server_mode)
    {
        if (!preg_match("/^[A-Za-z0-9\.\-_]+$/", $server_mode))
            return lang('web_server_server_name_invalid');
    }
    
    function get_server_upsd()
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
            $retval = $file->lookup_value("/^UPSD_OPTIONS\s+/i");
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }
    
    function set_server_upsd($server_upsd)
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
        $match = $file->replace_lines("/^\s*UPSD_OPTIONS/i", "UPSD_OPTIONS $server_upsd\n");

        if (! $match) {
            $match = $file->replace_lines("/^#UPSD_OPTIONS/i", "UPSD_OPTIONS $server_upsd\n");

            if (! $match)
                $file->add_lines_after("UPSD_OPTIONS $server_upsd\n", "/^[^#]/");
        }
    }
    
    function get_server_upsmon()
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
            $retval = $file->lookup_value("/^UPSMON_OPTIONS\s+/i");
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }
    
    function set_server_upsmon($server_upsmon)
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
        $match = $file->replace_lines("/^\s*UPSMON_OPTIONS/i", "UPSMON_OPTIONS $server_upsmon\n");

        if (! $match) {
            $match = $file->replace_lines("/^#UPSMON_OPTIONS/i", "UPSMON_OPTIONS $server_upsmon\n");

            if (! $match)
                $file->add_lines_after("UPSMON_OPTIONS $server_upsmon\n", "/^[^#]/");
        }
    }
    
    function get_server_poweroff_wait()
    {
       clearos_profile(__METHOD__, __LINE__);

        try {
            $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
            $retval = $file->lookup_value("/^POWEROFF_WAIT\s+/i");
        } catch (File_No_Match_Exception $e) {
            return '';
        } catch (Exception $e) {
            throw new Engine_Exception(clearos_exception_message($e), CLEAROS_ERROR);
        }
        return $retval;
    }

    function set_server_poweroff_wait($server_poweroff_wait)
    {
        clearos_profile(__METHOD__, __LINE__);

        $file = new File(clearos_app_base('ups_server'). "/packaging/" . self::FILE_CONFIG);
        $match = $file->replace_lines("/^\s*POWEROFF_WAIT/i", "POWEROFF_WAIT $server_poweroff_wait\n");

        if (! $match) {
            $match = $file->replace_lines("/^#POWEROFF_WAIT/i", "POWEROFF_WAIT $server_poweroff_wait\n");

            if (! $match)
                $file->add_lines_after("POWEROFF_WAIT $server_poweroff_wait\n", "/^[^#]/");
        }
    }
    
    function get_ups_list($item, $value)
    {
        $list[1]['name'] = 'ups1';
        $list[1]['desc'] = 'Primary UPS';
        $list[1]['driver'] = 'auto';
        $list[1]['port'] = '3493';
        $list[1]['sorder'] = 'null';
        $list[1]['nolock'] = 'null';
        $list[1]['ignorelb'] = 'null';
        $list[1]['maxstartdelay'] = '10';
        
        $list[2]['name'] = 'ups2';
        $list[2]['desc'] = 'Secondary UPS';
        $list[2]['driver'] = 'auto';
        $list[2]['port'] = '3493';
        $list[2]['sorder'] = 'null';
        $list[2]['nolock'] = 'null';
        $list[2]['ignorelb'] = 'null';
        $list[2]['maxstartdelay'] = '5';
        
        if (! $item) {
            return $list;
        } else {
            return $list[$item][$value];
        }
    }
    
    function get_ups_commands_list($item, $value)
    {
        $list[1]['command'] = 'battery.charge';
        $list[1]['default'] = '100';
        $list[1]['override'] = '100';
        $list[2]['command'] = 'battery.charge.low';
        $list[2]['default'] = '10';
        $list[2]['override'] = '30';
        $list[3]['command'] = 'battery.charge.warning';
        $list[3]['default'] = '50';
        $list[3]['override'] = '50';
        $list[4]['command'] = 'battery.runtime.low';
        $list[4]['default'] = '80';
        $list[4]['override'] = '85';
        $list[5]['command'] = 'input.voltage.nominal';
        $list[5]['default'] = '230';
        $list[5]['override'] = '240';
        $list[6]['command'] = 'input.voltage.nominal';
        $list[6]['default'] = '200';
        $list[6]['override'] = '210';
        $list[7]['command'] = 'battery.date';
        $list[7]['default'] = 'yyyy/mm/dd';
        $list[7]['override'] = '2011/01/15';
        $list[8]['command'] = 'battery.mfr.date';
        $list[8]['default'] = 'yyyy/mm/dd';
        $list[8]['override'] = '2011/01/15';
        $list[9]['command'] = 'battery.runtime';
        $list[9]['default'] = '2000';
        $list[9]['override'] = '3000';
        $list[10]['command'] = 'battery.type';
        $list[10]['default'] = 'PbAc';
        $list[10]['override'] = 'PbAc';
        
        if (! $item) {
            return $list;
        } else {
            return $list[$item][$value];
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
