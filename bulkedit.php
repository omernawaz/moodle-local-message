<?php
// This file is part of Moodle Course Rollover Plugin
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
 * @package     local_message
 * @author      Kristian
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use local_message\form\bulkedit;
use local_message\message_handler;

require_once(__DIR__ . '/../../config.php');




require_login();
require_capability('local/message:manage', \context_system::instance());


$PAGE->set_url(new moodle_url('/local/message/bulkedit.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('bulk:title', 'local_message'));
$PAGE->set_heading(get_string('bulk:heading', 'local_message'));


function process_data_for_update($messageids, $formdata)
{
    $processed_data = array();
    foreach ($messageids as $messageid) {
        $processed_data[$messageid] = array('body' => $formdata['body_' . $messageid], 'type' => $formdata['type_' . $messageid]);
    }

    return $processed_data;
}




$messageid = optional_param('messageid', null, PARAM_INT);

// We want to display our form.
$mform = new bulkedit();
$handler = new message_handler();

if ($mform->is_cancelled()) {
    // Go back to manage.php page
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('bulk:cancel', 'local_message'));
} else if ($fromform = $mform->get_data()) {
    $formdata = (array)$fromform;
    $messageids = [];
    foreach ($formdata as $key => $data) {
        if (str_contains($key, 'select_') && $data === '1') {
            $messageids[] = substr($key, 7);
        }
    }

    if ($messageids) {
        if ($fromform->editaction === '1') {
            $handler->delete_messages($messageids);
            redirect($CFG->wwwroot . '/local/message/manage.php', get_string('bulk:deletesuccess', 'local_message'));
        } else {
            $handler->update_messages(process_data_for_update($messageids, $formdata));
            redirect($CFG->wwwroot . '/local/message/manage.php', get_string('bulk:editsuccess', 'local_message'));
        }
    }
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
