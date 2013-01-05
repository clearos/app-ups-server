<?php

$this->lang->load('base');
$this->lang->load('ups_server');

for ($score = 5; $score <= 60; $score+= 5) {
    switch ((int)$score) {
        case 15:
            $upsd_conf_maxage_options[$score] = $score . ' - ' . lang('ups_server_default');
            break;
        default:
            $upsd_conf_maxage_options[$score] = $score;
    }
}

if ($form_type === 'edit') {
	$read_only = FALSE;
	$buttons = array (
		form_submit_update('submit'),
		anchor_cancel('/app/ups_server/upsd_conf_settings')
	);
} else {
	$read_only = TRUE;
    $buttons = array(
        anchor_edit('/app/ups_server/upsd_conf_settings/edit'),
		anchor_cancel('/app/ups_server')
    );
}

echo form_open('ups_server/upsd_conf_settings/edit');
echo form_header(lang('base_settings'));
//REMOVE AFTER TESTING
echo fieldset_header('TAG: UPS.CONF CONFIGURATION DIRECTIVES<br>TAG: CONTROLLER = UPSD_CONF_SETTINGS.PHP<br>TAG: VIEW = "/UPSD_CONF/SETTINGS.PHP"');
//REMOVE AFTER TESTING

echo field_dropdown('upsd_conf_maxage', $upsd_conf_maxage_options, $upsd_conf_maxage, 'MAXAGE', $read_only);
echo field_input('upsd_conf_statepath', $upsd_conf_statepath, 'STATE PATH', $read_only);
echo field_input('upsd_conf_maxconn', $upsd_conf_maxconn, 'MAX CONNECTIONS', $read_only);
echo field_input('upsd_conf_certfile', $upsd_conf_certfile, 'CERTIFICATE FILE', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
