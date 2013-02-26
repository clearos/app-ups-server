<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/'.$dir.'/settings');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF GLOBAL DIRECTIVES<br>TAG: CONTROLLER = UPS_CONF/SETTINGS.PHP<br>TAG: VIEW = "/UPS_CONF/SETTINGS.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

if ($form_type === 'edit') {
    $read_only = FALSE;
    $form = 'ups_server/'.$dir.'/settings/edit';
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server/'.$dir.'/settings')
    );
} else {
    $read_only = TRUE;
    $form = 'ups_server/'.$dir.'/settings/edit';
    $buttons = array(
        anchor_edit('/app/ups_server/'.$dir.'/settings/edit'),
        anchor_cancel('/app/ups_server')
    );
}

echo form_open($form);
echo form_header(lang('base_settings'));

echo field_input('chroot', $chroot, 'CHROOT', $read_only);
echo field_input('driverpath', $driverpath, 'DRIVER PATH', $read_only);
echo field_input('maxstartdeley', $maxstartdelay, 'MAX START DELAY', $read_only);
echo field_input('pollinterval', $pollinterval, 'POLL INTERVAL', $read_only);
echo field_input('user', $user, 'USER', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
