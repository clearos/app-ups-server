<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/summary_edit/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF UPS FIELDS<br>TAG: CONTROLLER = UPS_CONF/SUMMARY_EDIT.PHP<br>TAG: VIEW = "/UPS_CONF/SUMMARY_EDIT.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$this->lang->load('base');
$this->lang->load('ups_server');

for ($score = 5; $score <= 60; $score+= 5) {
    switch ((int)$score) {
        case 45:
            $maxstartdelay_options[$score] = $score . ' - ' . lang('ups_server_default');
            break;
        default:
            $maxstartdelay_options[$score] = $score;
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
    $maxstartdelay = 45;
    $buttons = array(
        anchor_add(''),
        anchor_cancel('/app/ups_server')
    );
}

echo form_open('ups_server/summary_edit/');
echo form_header(lang('base_settings'));
echo field_input('name', $name, 'NAME', $read_only);
echo field_input('driver', $driver, 'DRIVER', $read_only);
echo field_input('port', $port, 'PORT', $read_only);
echo field_input('sdorder', $sdorder, 'SDORDER', $read_only);
echo field_input('desc', $desc, 'DESCRIPTION', $read_only);
echo field_input('nolock', $nolock, 'NOLOCK', $read_only);
echo field_input('ignorelb', $ignorelb, 'IGNORELB', $read_only);
echo field_dropdown('maxstartdelay', $maxstartdelay_options, $maxstartdelay, 'MAXSTARTDELAY', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
