<?php

class ReminderModel {

    private static $reminderMaxLength = 100;

    private $reminder;

    public function __construct(string $reminder) {
        $this->setReminder($reminder);
    }

    private function setReminder(string $reminder) {
        if (strlen($reminder) > self::$reminderMaxLength) {
            throw new Exception("The reminder is too long");
        } else {
            $this->reminder = $reminder;
        }
    }
    
    public function getReminder() : string {
        return $this->reminder;
    }
}