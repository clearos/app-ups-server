<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_conf_summary_view/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPSD.CONF VIEW<br>TAG: CONTROLLER = UPSD_CONF_SUMMARY_VIEW.PHP<br>TAG: VIEW = "/UPSD_CONF/SUMMARY_VIEW.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$headers = array(
    lang('ups_server_server_interface'),
    lang('ups_server_server_port'),
);

$anchors = array(anchor_custom('/app/ups_server/upsd_conf_settings/', 'Configuration Directives'),anchor_add('/app/ups_server/upsd_conf_summary_edit/add'));

foreach ($upsd_conf_interfaces as $id => $details) {

    $detail_buttons = button_set(
        array(
            anchor_edit('/app/ups_server/upsd_conf_summary_edit/edit/' . $id),
            anchor_delete('/app/ups_server/upsd_conf_summary_edit/delete/' . $id)
        )
    );

    $item['title'] = $details['ip'];
    $item['action'] = '##' . $id;
    $item['anchors'] = $detail_buttons;
    $item['details'] = array(
        $details['ip'],
        $details['port']
    );

    $items[] = $item;
}

echo summary_table(
    lang('ups_server_interface_list'),
    $anchors,
    $headers,
    $items
);
