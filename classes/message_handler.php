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

namespace local_message;

use dml_exception;
use stdClass;

class message_handler
{

    private function get_type_text($type_index)
    {

        $choices = array();
        $choices['0'] = 'info';
        $choices['1'] = 'success';
        $choices['2'] = 'warning';
        $choices['3'] = 'danger';

        return $choices[$type_index];
    }

    public function get_messages_user(int $userid): array
    {
        global $DB;

        $sql = "SELECT lm.id,lm.body,lm.type,lm.typetext FROM {local_message_messages} lm 
        LEFT JOIN {local_message_messages_read} lmr 
        ON lm.id = lmr.message_id AND lmr.user_id = :userid
        WHERE lmr.user_id IS NULL;
        ";

        $params = [
            'userid' => $userid,
        ];

        try {
            return $DB->get_records_sql($sql, $params);
        } catch (dml_exception $e) {
            echo $e;
            return array();
        }
    }

    public function get_messages_all(bool $recentFirst = true): array
    {
        $sort = $recentFirst ? 'id desc' : '';
        global $DB;
        return $DB->get_records('local_message_messages', null, $sort);
    }

    public function get_message_by_id(int $messageid): object
    {
        global $DB;
        return $DB->get_record('local_message_messages', ['id' => $messageid]);
    }

    public function create_message(array $body, array $type): string
    {

        global $DB;

        $recordsno = count($body);
        $messages = array_values($body);
        $types = array_values($type);

        $recordtoinsert = new stdClass();
        $returnmsg = get_string('add:added', 'local_message');
        for ($i = 0; $i < $recordsno; $i++) {
            $recordtoinsert->body = $messages[$i];
            $recordtoinsert->type = $types[$i];
            $recordtoinsert->typetext = self::get_type_text($types[$i]);

            try {
                $DB->insert_record('local_message_messages', $recordtoinsert, false, false);
                $returnmsg .= '<br>' . $messages[$i];
            } catch (dml_exception $e) {
                return "Error $e";
            }
        }

        return $returnmsg;
    }

    public function mark_message_read(int $messageid, int $userid): bool
    {

        global $DB;

        $readrecord = new stdClass();
        $readrecord->message_id = $messageid;
        $readrecord->user_id = $userid;
        $readrecord->time_read = time();

        try {
            return $DB->insert_record('local_message_messages_read', $readrecord, false);
        } catch (dml_exception $e) {
            return false;
        }
    }

    public function update_message(int $messageid, string $body, string $type)
    {

        global $DB;

        $updaterecord = new stdClass();

        $updaterecord->id = $messageid;
        $updaterecord->body = $body;
        $updaterecord->type = $type;
        $updaterecord->typetext = self::get_type_text($type);

        $DB->update_record('local_message_messages', $updaterecord);
        $DB->delete_records('local_message_messages_read', ['message_id' => $updaterecord->id]);
    }

    public function delete_message(int $messageid): bool
    {
        global $DB;
        $trans = $DB->start_delegated_transaction();
        $deleteMessage = $DB->delete_records('local_message_messages', ['id' => $messageid]);
        $deleteRead = $DB->delete_records('local_message_messages_read', ['message_id' => $messageid]);

        if ($deleteMessage && $deleteRead) {
            $DB->commit_delegated_transaction($trans);
        }

        return true;
    }

    public function delete_messages(array $messageids): bool
    {
        global $DB;
        $trans = $DB->start_delegated_transaction();
        foreach ($messageids as $messageid) {
            $deleteMessage = $DB->delete_records('local_message_messages', ['id' => $messageid]);
            $deleteRead = $DB->delete_records('local_message_messages_read', ['message_id' => $messageid]);
        }
        if ($deleteMessage && $deleteRead) {
            $DB->commit_delegated_transaction($trans);
        }
        return true;
    }

    public function update_messages(array $data)
    {

        global $DB;
        $updaterecord = new stdClass();

        foreach ($data as $id => $record) {

            $updaterecord->id = $id;
            $updaterecord->body = $record['body'];
            $updaterecord->type = $record['type'];
            $updaterecord->typetext = self::get_type_text($record['type']);

            $DB->update_record('local_message_messages', $updaterecord);
            $DB->delete_records('local_message_messages_read', ['message_id' => $updaterecord->id]);
        }
    }
}
