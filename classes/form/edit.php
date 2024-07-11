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

namespace local_message\form;

use moodleform;

require_once("$CFG->libdir/formslib.php");


class edit extends moodleform
{
    public function definition()
    {

        $choices = array(
            '0' => \core\output\notification::NOTIFY_INFO,
            '1' => \core\output\notification::NOTIFY_SUCCESS,
            '2' => \core\output\notification::NOTIFY_WARNING,
            '3' => \core\output\notification::NOTIFY_ERROR
        );


        $mform = $this->_form; // Don't forget the underscore!

        $mform->addElement('text', 'body', get_string('edit:messagebody', 'local_message'));
        $mform->addElement('select', 'type', get_string('edit:messagetype', 'local_message'), $choices);
        $mform->addElement('hidden', 'id');

        $mform->setDefault('type', '0');
        $mform->setDefault('body', get_string('edit:messagebodydefault', 'local_message'));


        $mform->setType('body', PARAM_NOTAGS);
        $mform->setType('id', PARAM_INT);

        $this->add_action_buttons();
    }

    // Custom validation should be added here.
    function validation($data, $files)
    {
        return [];
    }
}
