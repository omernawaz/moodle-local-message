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

require_once(__DIR__ . '/../../config.php');



$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('manage:title', 'local_message'));
$PAGE->set_heading(get_string('manage:title', 'local_message'));
$PAGE->requires->js_call_amd('local_message/confirm');

require_login();
require_capability('local/message:manage', \context_system::instance());
// if (!is_siteadmin($USER))
//     redirect($CFG->wwwroot, get_string('manage:notadmin', 'local_message'), null, \core\output\notification::NOTIFY_ERROR);

$handler = new message_handler();
$messages = $handler->get_messages_all();

if (empty($messages)) {
    $title = get_string('manage:nomessage', 'local_message');
} else {
    $title = get_string('manage:list', 'local_message');
}

$templatecontext = (object)[
    'title' => $title,
    'messages' => array_values($messages),
    'editurl' => new moodle_url("/local/message/edit.php"),
    'addurl' => new moodle_url("/local/message/add.php"),
    'bulkediturl' => new moodle_url("/local/message/bulkedit.php")

];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_message/manage', $templatecontext);

echo $OUTPUT->footer();
