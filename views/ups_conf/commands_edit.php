<?php
//MERGE VIEWS COMMANDS_EDIT & COMMANDS_VIEW?

$this->lang->load('base');
$this->lang->load('ups_server');

if ($form_type === 'edit') {
    $read_only = FALSE;
    $buttons = array (
        form_submit_update('submit'),
        anchor_cancel('/app/ups_server/ups_conf_commands_view')
    );
} else {
    $read_only = FALSE;
    $buttons = array(
        anchor_add(''),
        anchor_cancel('/app/ups_server/ups_conf_commands_view')
    );
}

echo form_open('ups_server/ups_conf_commnads_edit/');
echo form_header(lang('base_settings'));
//REMOVE AFTER TESTING
echo fieldset_header('TAG: UPS.CONF COMMANDS ADD & EDIT<br>TAG: CONTROLLER = UPS_CONF_COMMANDS_EDIT.PHP<br>TAG: VIEW = "/UPS_CONF/COMMANDS_EDIT.PHP"');
//REMOVE AFTER TESTING
echo field_input('ups_commands_command', $ups_commands_command, 'COMMAND VALUE', $read_only);
echo field_input('ups_commands_default', $ups_commands_default, 'DEFAULT VALUE', $read_only);
echo field_input('ups_commands_override', $ups_commands_override, 'OVERRIDE VALUE', $read_only);

echo field_button_set($buttons);

echo form_footer();
echo form_close();
