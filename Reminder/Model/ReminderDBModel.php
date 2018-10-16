<?php

class ReminderDBModel {

    private static $path = "./reminders.xml";
    private static $reminder = "reminder";
    private static $text = "text";


    //SHOULD IMPLEMENT A DATABASE.
    public function getAllReminders() : array {
        $reminders = array();
        $xml = simplexml_load_file(self::$path);
        foreach($xml->children() as $child) { 
            array_push($reminders, $child[self::$text]); 
        }
        return $reminders;
    }

    public function createReminder(ReminderModel $reminderModel) {

        $reminder = $reminderModel->getReminder();
        $xml = simplexml_load_file(self::$path);
        $newReminder = $xml->addChild(self::$reminder);
        $newReminder->addAttribute(self::$text, $reminder);
        $xml->asXml(self::$path);
    }

    public function deleteReminder(ReminderModel $reminderModel) {
        $reminder = $reminderModel->getReminder();
        $xml = simplexml_load_file(self::$path);
        foreach($xml->children() as $child)
        {
            if($child[self::$text] == $reminder) { unset($child[0]); }
        }

        $xml->asXml(self::$path);
    }
}