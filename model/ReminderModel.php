<?php

class ReminderModel {

    private $reminder;

    public function setReminder(string $reminder)  {
		$this->reminder = $reminder;
    }
    
    public function getReminder() : string {
        return $this->reminder;
    }
}