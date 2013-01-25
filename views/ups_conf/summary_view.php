<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/ups_conf_commnads_view/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPS.CONF VIEW<br>TAG: CONTROLLER = UPS_CONF_SUMMARY_VIEW.PHP<br>TAG: VIEW = "/UPS_CONF/SUMMARY_VIEW.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$headers = array(
    lang('ups_server_ups_name'),
    lang('ups_server_description'),
);

$anchors = array(anchor_custom('/app/ups_server/ups_conf_settings/', 'Global Directives'),anchor_add('/app/ups_server/ups_conf_summary_edit/add'));

foreach ($ups_conf_list as $id => $details) {

    $detail_buttons = button_set(
        array(
            anchor_custom('/app/ups_server/ups_conf_commands_view/edit/' . $id, lang('base_configure')),
            anchor_edit('/app/ups_server/ups_conf_summary_edit/edit/' . $id),
            anchor_delete('/app/ups_server/ups_conf_summary_edit/delete/' . $id)
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
