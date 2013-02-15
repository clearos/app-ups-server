<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_users/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF USERS ADD & EDIT<br>TAG: CONTROLLER = UPSD_USERS.PHP<br>TAG: VIEW = "/UPSD_USERS/USERS.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

$upsd_user_upsmon_options = array(
    'slave' => 'slave',
    'master' => 'master',
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

echo form_open('ups_server/upsd_users/');
echo form_header(lang('base_settings'));
echo field_input('upsd_user_name', $upsd_user_name, 'USER NAME', $read_only);
echo field_input('upsd_user_pwd', $upsd_user_pwd, 'USER PWD', $read_only);
echo field_checkbox('upsd_user_actions_set', $upsd_user_actions_set, 'Actions SET');
echo field_checkbox('upsd_user_actions_fsd', $upsd_user_actions_fsd, 'Actions FSD');
echo field_dropdown('upsd_user_upsmon', $upsd_user_upsmon_options, $upsd_user_upsmon, 'UPSMON', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
