<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package   local_message
 * @copyright 2024, Omer Nawaz <omarnawaz29@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


function get_type($message){

    switch($message->typetext){
        case 'info':
            return \core\output\notification::NOTIFY_INFO;
            break;
        case 'success':
            return \core\output\notification::NOTIFY_SUCCESS;
            break;
        case 'warning':
            return \core\output\notification::NOTIFY_WARNING;
            break;
        case 'danger':
            return \core\output\notification::NOTIFY_ERROR;
            break;
    }
}

function insert_read_record($message) {

    global $USER;
    global $DB;

    $readrecord = new stdClass();
    $readrecord->message_id = $message->id;
    $readrecord->user_id = $USER->id;
    $readrecord->time_read = time();

    $DB->insert_record('local_message_messages_read', $readrecord);
}

function local_message_before_footer() {

    global $DB,$USER;

    if($USER->id == 0)
        return;

    //$messages = $DB->get_records('local_message_messages');

    $sql = "SELECT lm.id,lm.body,lm.type,lm.typetext FROM {local_message_messages} lm 
        LEFT JOIN {local_message_messages_read} lmr 
        ON lm.id = lmr.message_id AND lmr.user_id = :userid
        WHERE lmr.user_id IS NULL;
    ";

    $params = [
        'userid' => $USER->id,
    ];

    $messages = $DB->get_records_sql($sql,$params);



    foreach($messages as $message){

        \core\notification::add($message->body, get_type($message));

       insert_read_record($message);
    }
}