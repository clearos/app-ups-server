<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/commnads_view/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF COMMANDS VIEW<br>TAG: CONTROLLER = UPS_CONF/COMMANDS_VIEW.PHP<br>TAG: VIEW = "/UPS_CONF/COMMANDS_VIEW.PHP"');
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

$anchors = array(anchor_add('/app/ups_server/'.$dir.'/commands_edit/add/'.$ups),anchor_cancel('/app/ups_server/'));

foreach ($ups_commands_list as $id => $details) {
    if ($id != 0) 
    {
        if ($details['edit'] === 'custom')
        {
            $detail_buttons = button_set(
                array(
                    anchor_edit('/app/ups_server/'.$dir.'/commands_edit/edit/'.$id.'/'.$ups),
                    anchor_delete('/app/ups_server/'.$dir.'/commands_edit/delete/'.$details['command'].'/'.$ups)
                )
            );
        } else {
            if ($details['edit'] === 'unknown')
            {
                $detail_buttons = button_set(
                    array(
                        anchor_edit('/app/ups_server/'.$dir.'/commands_edit/edit/'.$id.'/'.$ups),
                        anchor_custom('/app/ups_server/'.$dir.'/commands_view/report/', 'Report')
                    )
                );
            } else {
                $detail_buttons = button_set(
                    array(
                        anchor_edit('/app/ups_server/'.$dir.'/commands_edit/edit/'.$id.'/'.$ups)
                    )
                );
            }
        }
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
    lang('ups_server_command_list'),
    $anchors,
    $headers,
    $items
);
