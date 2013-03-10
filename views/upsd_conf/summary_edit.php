<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_conf_summary_edit/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPSD.CONF LISTEN INTERFACE<br>TAG: CONTROLLER = UPSD_CONF_SUMMARY_EDIT.PHP<br>TAG: VIEW = "/UPSD_CONF/SUMMARY_EDIT.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

$ip_validate_options = array(
    'ipv4' => 'ipv4',
    'ipv6' => 'ipv6',
);

if ($form_type === 'edit') {
    $read_only = FALSE;
    $form = 'ups_server/'.$dir.'/upsd_conf_summary_edit/edit/'.$item;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server')
    );
} else {
    $read_only = FALSE;
    $form = 'ups_server/'.$dir.'/upsd_conf_summary_edit/add';
    $buttons = array(
        form_submit_add('submit'),
        anchor_cancel('/app/ups_server')
    );
    $ip = 'Required';
    $port = 'Optional: Default 3493';
}

echo form_open($form);
echo form_header(lang('base_settings'));
echo field_dropdown('ip_validate', $ip_validate_options, $ip_validate, 'IP VALIDATE', $read_only);
echo field_input('ip_old', $ip, 'OLD IP', $read_only, array('hide_field' => TRUE));
echo field_input('ip', $ip, 'SERVER IP', $read_only);
echo field_input('port', $port, 'SERVER PORT', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
