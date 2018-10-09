<?php

class ReminderController {
    private $reminder;
    private $reminderView;
    private $reminderDBModel;


    public function __construct (ReminderView $reminderView, ReminderDBModel $reminderDBModel) {
        $this->reminderView = $reminderView;
        $this->reminderDBModel = $reminderDBModel;

    }

    public function manageReminders() {

        if ($this->reminderView->userWantsCreateForm()) {}
            
        if ($this->reminderView->userWantsToCreateReminder()) {
            $this->reminder = $this->reminderView->getReminder();
            $this->reminderDBModel->createReminder($this->reminder);
                
        }
    }

    public function getReminderView() {
        return $this->reminderView;
    }
}