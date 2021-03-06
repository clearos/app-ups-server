<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_conf_settings/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPSD.CONF CONFIGURATION DIRECTIVES<br>TAG: CONTROLLER = UPSD_CONF_SETTINGS.PHP<br>TAG: VIEW = "/UPSD_CONF/SETTINGS.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

for ($score = 5; $score <= 60; $score+= 5) {
    switch ((int)$score) {
        case 15:
            $maxage_options[$score] = $score . ' - ' . lang('ups_server_default');
            break;
        default:
            $maxage_options[$score] = $score;
    }
}

if ($form_type === 'edit') {
    $read_only = FALSE;
    $form = 'ups_server/'.$dir.'/upsd_conf_settings/edit';
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server/'.$dir.'/upsd_conf_settings')
    );
} else {
    $read_only = TRUE;
    $form = 'ups_server/'.$dir.'/upsd_conf_settings/edit';
    $buttons = array(
        anchor_edit('/app/ups_server/'.$dir.'/upsd_conf_settings/edit'),
        anchor_cancel('/app/ups_server')
    );
}

echo form_open($form);
echo form_header(lang('base_settings'));
echo field_dropdown('maxage', $maxage_options, $maxage, 'MAXAGE', $read_only);
echo field_input('statepath', $statepath, 'STATE PATH', $read_only);
echo field_input('maxconn', $maxconn, 'MAX CONNECTIONS', $read_only);
echo field_input('certfile', $certfile, 'CERTIFICATE FILE', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();


