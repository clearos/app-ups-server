<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/summary_view');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF VIEW<br>TAG: CONTROLLER = UPS_CONF/SUMMARY_VIEW.PHP<br>TAG: VIEW = "/UPS_CONF/SUMMARY_VIEW.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$headers = array(
    lang('ups_server_ups_name'),
    lang('ups_server_description'),
);

$anchors = array(anchor_custom('/app/ups_server/'.$dir.'/settings', 'Global Directives'),anchor_add('/app/ups_server/'.$dir.'/summary_edit/add'));

foreach ($ups_conf_list as $id => $details) {

    $detail_buttons = button_set(
        array(
            anchor_custom('/app/ups_server/'.$dir.'/commands_view/edit/' . $details['name'], lang('base_configure')),
            anchor_edit('/app/ups_server/'.$dir.'/summary_edit/edit/' . $details['name']),
            anchor_delete('/app/ups_server/'.$dir.'/summary_edit/delete/' . $details['name'])
        )
    );

    $item['title'] = $details['name'];
    $item['action'] = '##' . $id;
    $item['anchors'] = $detail_buttons;
    $item['details'] = array(
        $details['name'],
        $details['desc']
    );

    $items[] = $item;
}

echo summary_table(
    lang('ups_server_ups_list'),
    $anchors,
    $headers,
    $items
);
