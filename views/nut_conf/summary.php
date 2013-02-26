<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/nut_conf');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: NUT.CONF<br>TAG: CONTROLLER = NUT_CONF/NUT_CONF.PHP<br>TAG: VIEW = "/NUT_CONF/SUMMARY.PHP"<br>TAG:COMPLETION = 100% FOR THIS VERSION.');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

$mode_options = array(
    'none' => 'none',
    'standalone' => 'standalone',
    'netserver' => 'netserver',
    'netclient' => 'netclient',
);

for ($score = 5; $score <= 60; $score+= 5) {
    switch ((int)$score) {
        case 15:
            $poweroff_wait_options[$score] = $score . ' - ' . lang('ups_server_default');
            break;
        default:
            $poweroff_wait_options[$score] = $score;
    }
}

if ($form_type === 'edit') {
    $read_only = FALSE;
    $form = 'ups_server/nut_conf/edit';
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server')
    );
} else {
    $read_only = TRUE;
    $form = 'ups_server/nut_conf/edit';
    $buttons = array(
        anchor_edit('/app/ups_server/nut_conf/edit')
    );
}

echo form_open($form);
echo form_header(lang('base_settings'));
echo field_dropdown('mode', $mode_options, $mode, lang('ups_server_server_mode'), $read_only);

if ($show_options) {
    echo field_input('upsd', $upsd, 'UPSD_OPTIONS', $read_only);
    echo field_input('upsmon', $upsmon, 'UPSMON_OPTIONS', $read_only);
}

echo field_dropdown('poweroff_wait', $poweroff_wait_options, $poweroff_wait, 'POWEROFF_WAIT', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
