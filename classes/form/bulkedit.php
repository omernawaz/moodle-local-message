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
 * @author      Omer Nawaz
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_message\form;

use local_message\message_handler;
use moodleform;

require_once("$CFG->libdir/formslib.php");

class bulkedit extends moodleform
{
    public function definition()
    {
        $mform = $this->_form; // Don't forget the underscore!

        // Display the list of messages with a checkbox.
        $handler = new message_handler();
        $messages = $handler->get_messages_all(false);


        $choices = array(
            '0' => \core\output\notification::NOTIFY_INFO,
            '1' => \core\output\notification::NOTIFY_SUCCESS,
            '2' => \core\output\notification::NOTIFY_WARNING,
            '3' => \core\output\notification::NOTIFY_ERROR
        );

        $actions = array('Edit', 'Delete');
        $mform->addElement('select', 'editaction', get_string('bulk:actiontip', 'local_message'), $actions);

        $mform->addElement('static', 'todo', get_string('bulk:select', 'local_message'));

        foreach ($messages as $message) {

            $mform->addElement('advcheckbox', 'select_' . $message->id, "Message: " . $message->id);
            $mform->addElement('textarea', 'body_' . $message->id, "Message: ");
            $mform->addElement('select', 'type_' . $message->id, "Type: ", $choices);

            $mform->disabledif('body_' . $message->id, 'editaction', 'eq', '1');
            $mform->disabledif('type_' . $message->id, 'editaction', 'eq', '1');

            $mform->disabledif('body_' . $message->id, 'select_' . $message->id, 'notchecked');
            $mform->disabledif('type_' . $message->id, 'select_' . $message->id, 'notchecked');

            $mform->setDefault('type_' . $message->id, $message->type);
            $mform->setDefault('body_' . $message->id, $message->body);

            $mform->setType("body_$message->id", PARAM_NOTAGS);
        }

        $mform->addElement('hidden', 'messagecount');
        $mform->setDefault('messagecount', count($messages));
        $mform->setType('messagecount', PARAM_INT);


        $actions = array('Edit', 'Delete');

        $this->add_action_buttons();
    }

    //Custom validation should be added here
    function validation($data, $files)
    {
        return array();
    }
}
