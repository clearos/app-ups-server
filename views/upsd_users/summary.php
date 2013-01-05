<?php
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_users_summary/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPSD_USERS.CONF VIEW<br>TAG: CONTROLLER = UPSD_USERS.PHP<br>TAG: VIEW = "/UPSD_USERS/SUMMARY.PHP"');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING

$headers = array(
    lang('ups_server_user_name'),
    lang('ups_server_server_mode'),
);

$anchors = array(anchor_add('/app/ups_server/upsd_users/add'));

foreach ($upsd_user_list as $id => $details) {

	$detail_buttons = button_set(
		array(
			anchor_custom('/app/ups_server/upsd_users_commands/edit/' . $id, 'Commands'),
			anchor_edit('/app/ups_server/upsd_users/edit/' . $id),
			anchor_delete('/app/ups_server/upsd_users/delete/' . $id)
		)
	);

    $item['title'] = $details['name'];
    $item['action'] = '##' . $id;
    $item['anchors'] = $detail_buttons;
    $item['details'] = array(
        $details['name'],
        $details['upsmon']
    );

    $items[] = $item;
}

echo summary_table(
    lang('ups_server_user_list'),
    $anchors,
    $headers,
    $items
);
