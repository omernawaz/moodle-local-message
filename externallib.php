<?php

/**
 * local_message external file
 *
 * @package    component
 * @category   external
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//namespace local_message;


defined('MOODLE_INTERNAL') || die();

use local_message\message_handler;

require_once($CFG->libdir . "/externallib.php");

class local_message_external extends external_api
{

    public static function delete_message_parameters()
    {
        return new external_function_parameters(
            ['messageid' => new external_value(PARAM_INT, 'id of message to delete')]
        );
    }


    public static function delete_message($messageid)
    {
        global $USER, $CFG;
        $params = self::validate_parameters(self::delete_message_parameters(), array('messageid' => $messageid));

        require_capability('local/message:manage', \context_system::instance());



        $handler = new message_handler();
        return $handler->delete_message($messageid);
    }


    public static function delete_message_returns()
    {
        return new external_value(PARAM_BOOL, 'True if the message was successfully deleted.');
    }
}
