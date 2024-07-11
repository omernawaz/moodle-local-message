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


use local_message\form\add;
use local_message\message_handler;

require_once(__DIR__ . '/../../config.php');

$PAGE->set_url(new moodle_url('/local/message/add.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('add:title', 'local_message'));
$PAGE->set_heading(get_string('add:title', 'local_message'));

require_login();
require_capability('local/message:manage', \context_system::instance());



$mform = new add();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('add:cancelled', 'local_message'));
} else if ($fromform = $mform->get_data()) {

    //$messages = insert_records_all($fromform);
    $handler = new message_handler();
    $return_string = $handler->create_message($fromform->body, $fromform->type);
    redirect($CFG->wwwroot . '/local/message/manage.php', $return_string);
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();
