<?php

$this->lang->load('base');
$this->lang->load('ups_server');

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server')
    );
} else {
    $read_only = TRUE;
    $buttons = array(
        //anchor_edit('/app/ups_server/upsmon_conf/edit')
    );
}

echo form_open('ups_server/nut_conf/summary/edit');
echo form_header(lang('base_settings'));
//REMOVE AFTER TESTING
echo fieldset_header('TAG: UPSMON.CONF<br>TAG: CONTROLLER = UPSMON_CONF.PHP<br>TAG: VIEW = "/UPSMON_CONF/SUMMARY.PHP"');
//REMOVE AFTER TESTING

echo field_input('deadtime', $deadtime, 'DEADTIME', $read_only);
echo field_input('finaldelay', $deadtime, 'FINALDELAY', $read_only);
echo field_input('hostsync', $deadtime, 'HOSTSYNC', $read_only);
echo field_input('minsupplies', $deadtime, 'MINSUPPLIES', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
