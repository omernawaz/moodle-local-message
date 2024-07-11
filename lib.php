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


use local_message\message_handler;



function get_type($message)
{

    switch ($message->typetext) {
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

function local_message_before_footer()
{
    global $USER;

    if (!isloggedin() || !get_config('local_message', 'enabled')) {
        return;
    }


    $handler = new message_handler();
    $messages = $handler->get_messages_user($USER->id);

    foreach ($messages as $message) {
        \core\notification::add($message->body, get_type($message));
        $handler->mark_message_read($message->id, $USER->id);
    }
}
