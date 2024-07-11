<?php
$functions = array(
    'local_message_delete_message' => array(
        'classname' => 'local_message_external',
        'methodname' => 'delete_message',
        'classpath' => '/local/message/externallib.php',
        'description' => 'deletes a message by id',
        'type' => 'write',
        'ajax' => true,
        'capabilities' => '',
    )
);
