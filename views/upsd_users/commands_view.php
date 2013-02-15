<?php

$headers = array(
    lang('ups_server_command'),
    Enabled,
);

$anchors = array(form_submit_update('submit'),anchor_cancel('/app/ups_server/'));

foreach ($upsd_users_command_list as $id => $details) {

    $detail_buttons = button_set(
        array(
            anchor_custom('/app/content_filter/policy/configure/' . $id, lang('base_configure')),
        )
    );

    $item['title'] = $details['command'];
    $item['action'] = '/app/content_filter/policy/configure/' . $id;
    $item['anchors'] = $detail_buttons;
    $item['details'] = array(
        $details['command'],
        $details['chkd']
    );

    $items[] = $item;
}

echo list_table(
    lang('ups_server_command_list'),
    $anchors,
    $headers,
    $items
);
//ISSUE WITH THIS CODE BEING IN FRONT OF LIST TABLE? TRY FORM_OPEN/CLOSE AROUND TABLE?
//REMOVE AFTER TESTING
echo form_open('ups_server/upsd_users_commands/');
echo form_header('TESTING, NOTES.');
echo fieldset_header('TAG: UPSD_USERS.CONF COMMANDS<br>TAG: CONTROLLER = UPSD_USERS_COMMANDS.PHP<br>TAG: VIEW = "/UPSD_USERS/COMMANDS_VIEW.PHP"');
echo field_info('');
echo form_footer();
echo form_close();
//REMOVE AFTER TESTING