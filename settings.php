<?php

if (!$hassiteconfig)
    exit;

$ADMIN->add('localplugins', new admin_category('local_message_category', get_string('message:name', 'local_message')));

$settings = new admin_settingpage('local_message', get_string('message:name', 'local_message'));
$ADMIN->add('local_message_category', $settings);

$settings->add(new admin_setting_configcheckbox(
    'local_message/enabled',
    get_string('message:enable', 'local_message'),
    get_string('message:desc', 'local_message'),
    '1'
));

$ADMIN->add('local_message_category', new admin_externalpage(
    'local_message_manage',
    get_string('message:manage', 'local_message'),
    $CFG->wwwroot . '/local/message/manage.php'
));
