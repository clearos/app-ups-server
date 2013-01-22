<?php

$this->lang->load('base');
$this->lang->load('ups_server');

$upsd_conf_ip_validate_options = array(
    'ipv4' => 'ipv4',
    'ipv6' => 'ipv6',
);

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server')
    );
} else {
    $read_only = FALSE;
    $buttons = array(
        anchor_add(''),
        anchor_cancel('/app/ups_server')
    );
}

echo form_open('ups_server/upsd_conf_summary_edit/');
echo form_header(lang('base_settings'));
//REMOVE AFTER TESTING
echo fieldset_header('TAG: UPSD.CONF LISTEN INTERFACE<br>TAG: CONTROLLER = UPSD_CONF_SUMMARY_EDIT.PHP<br>TAG: VIEW = "/UPSD_CONF/SUMMARY_EDIT.PHP"');
//REMOVE AFTER TESTING

echo field_dropdown('upsd_conf_ip_validate', $upsd_conf_ip_validate_options, $upsd_conf_ip_validate, 'IP VALIDATE', $read_only);
echo field_input('upsd_conf_ip', $upsd_conf_ip, 'SERVER IP', $read_only);
echo field_input('upsd_conf_port', $upsd_conf_port, 'SERVER PORT', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
