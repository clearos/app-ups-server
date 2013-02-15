<?php
//MERGE VIEWS COMMANDS_EDIT & COMMANDS_VIEW.

//REMOVE AFTER TESTING
echo form_open('ups_server/ups_conf_commnads_view/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF COMMANDS VIEW<br>TAG: CONTROLLER = UPS_CONF_COMMANDS_VIEW.PHP<br>TAG: VIEW = "/UPS_CONF/COMMANDS_VIEW.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$headers = array(
    lang('ups_server_command'),
    lang('ups_server_default'),
    lang('ups_server_override'),
    lang('ups_server_supported'),
);

$anchors = array(anchor_add('/app/ups_server/ups_conf_commands_edit/add'),anchor_cancel('/app/ups_server/'));

foreach ($ups_commands_list as $id => $details) {
    if ($id != 0) 
    {
        $detail_buttons = button_set(
            array(
                anchor_edit('/app/ups_server/ups_conf_commands_edit/edit/' . $id . '/' . $ups),
                anchor_delete('/app/ups_server/ups_conf_commands_edit/delete/' . $id)
            )
        );

        $item['title'] = $details['name'];
        $item['action'] = '##' . $id;
        $item['anchors'] = $detail_buttons;
        $item['details'] = array(
            $details['command'],
            $details['default'],
            $details['override'],
            $details['supported']
        );
        $items[] = $item;
    }
}

echo summary_table(

    lang('ups_server_command_list').' : '.$ups,
    $anchors,
    $headers,
    $items
);
