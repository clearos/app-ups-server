<?php
class ups_server extends ClearOS_Controller
{
    function index()
    {
        $this->lang->load('ups_server');
        
         $views = array(
            'ups_server/nut_conf/nut_conf',
            'ups_server/ups_conf/summary_view',
            //'ups_server/upsd_conf_summary_view',
            //'ups_server/upsd_users',
            //'ups_server/upsmon_conf'
        );

        $this->page->view_forms($views, lang('ups_server_app_name'));
    }
}
