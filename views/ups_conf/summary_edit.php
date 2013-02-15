<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/ups_conf_summary_edit/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF UPS FIELDS<br>TAG: CONTROLLER = UPS_CONF_SUMMARY_EDIT.PHP<br>TAG: VIEW = "/UPS_CONF/SUMMARY_EDIT.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$this->lang->load('base');
$this->lang->load('ups_server');

for ($score = 5; $score <= 60; $score+= 5) {
    switch ((int)$score) {
        case 45:
            $ups_conf_maxstartdelay_options[$score] = $score . ' - ' . lang('ups_server_default');
            break;
        default:
            $ups_conf_maxstartdelay_options[$score] = $score;
    }
}

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server')
    );
} else {
    $read_only = FALSE;
    $ups_conf_maxstartdelay = 45;
    $buttons = array(
        anchor_add(''),
        anchor_cancel('/app/ups_server')
    );
}

echo form_open('ups_server/ups_conf_summary_edit/');
echo form_header(lang('base_settings'));
echo field_input('ups_conf_name', $ups_conf_name, 'NAME', $read_only);
echo field_input('ups_conf_driver', $ups_conf_driver, 'DRIVER', $read_only);
echo field_input('ups_conf_port', $ups_conf_port, 'PORT', $read_only);
echo field_input('ups_conf_sdorder', $ups_conf_sdorder, 'SDORDER', $read_only);
echo field_input('ups_conf_desc', $ups_conf_desc, 'DESCRIPTION', $read_only);
echo field_input('ups_conf_nolock', $ups_conf_nolock, 'NOLOCK', $read_only);
echo field_input('ups_conf_ignorelb', $ups_conf_ignorelb, 'IGNORELB', $read_only);
echo field_dropdown('ups_conf_maxstartdelay', $ups_conf_maxstartdelay_options, $ups_conf_maxstartdelay, 'MAXSTARTDELAY', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
