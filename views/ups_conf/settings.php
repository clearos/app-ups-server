<?php

$this->lang->load('base');
$this->lang->load('ups_server');

if ($form_type === 'edit') {
	$read_only = FALSE;
	$buttons = array (
		form_submit_update('submit'),
		anchor_cancel('/app/ups_server/ups_conf_settings')
	);
} else {
	$read_only = TRUE;
    $buttons = array(
        anchor_edit('/app/ups_server/ups_conf_settings/edit'),
		anchor_cancel('/app/ups_server')
    );
}

echo form_open('ups_server/ups_conf_settings/edit');
echo form_header(lang('base_settings'));
//Remove after testing
echo fieldset_header('TAG: UPS.CONF GLOBAL DIRECTIVES<br>TAG: CONTROLLER = UPS_CONF_SETTINGS.PHP<br>TAG: VIEW = "/UPS_CONF/SETTINGS.PHP"');
//Remove after testing

echo field_input('ups_conf_chroot', $ups_conf_chroot, 'CHROOT', $read_only);
echo field_input('ups_conf_drivepath', $ups_conf_drivepath, 'DRIVE PATH', $read_only);
echo field_input('ups_conf_maxstartdeley', $ups_conf_maxstartdelay, 'MAX START DELAY', $read_only);
echo field_input('ups_conf_pollinterval', $ups_conf_pollinterval, 'POLL INTERVAL', $read_only);
echo field_input('ups_conf_user', $ups_conf_user, 'USER', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
