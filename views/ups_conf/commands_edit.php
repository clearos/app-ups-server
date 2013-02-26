<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/'.$dir.'/commnads_edit/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF COMMANDS ADD & EDIT<br>TAG: CONTROLLER = UPS_CONF/COMMANDS_EDIT.PHP<br>TAG: VIEW = "/UPS_CONF/COMMANDS_EDIT.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING
$this->lang->load('base');
$this->lang->load('ups_server');

if ($form_type === 'edit') {
    $read_only = FALSE;
    //FIX: null
    $form = 'ups_server/'.$dir.'/commands_edit/edit/null/'.$ups;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server/'.$dir.'/commands_view/view/'.$ups)
    );
} else {
    $read_only = FALSE;
    $form = 'ups_server/'.$dir.'/commands_edit/add_custom/'.$ups;
    $buttons = array(
        form_submit_add('submit'),
        anchor_cancel('/app/ups_server/'.$dir.'/commands_view/view/'.$ups)
    );
}

echo form_open($form);
echo form_header(lang('base_settings'));

if ($form_type === 'edit') {
    echo field_input('command', $command, 'COMMAND VALUE', TRUE);
    echo field_input('default', $default, 'DEFAULT VALUE', $read_only);
    echo field_input('override', $override, 'OVERRIDE VALUE', $read_only);
} else {
    echo field_input('command', $command, 'COMMAND VALUE', $read_only);
}

echo field_button_set($buttons);

echo form_footer();
echo form_close();