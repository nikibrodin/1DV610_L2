<?php

class ReminderDBModel {

    //private $myUsername = "Niki";

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function getAllReminders() : array {

        $reminders = array();

        $xml = simplexml_load_file("./reminders.xml");

        foreach($xml->children() as $child) {
            array_push($reminders, $child["text"]);
        }

        return $reminders;

    }


    public function createReminder(ReminderModel $reminderModel) {

        $reminder = $reminderModel->getReminder();

        $xml = simplexml_load_file("./reminders.xml");

        $user = $xml->addChild('reminder');
        $user->addAttribute('text', $reminder);

        $xml->asXml("./reminders.xml");
    }
}