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

        if ($this->reminderView->userWantsCreateForm()) {
            $this->reminderView->displayCreateForm();
        }
            
        if ($this->reminderView->userWantsToCreateReminder()) {

            $this->reminder = $this->reminderView->getReminder();
            $this->reminderDBModel->createReminder($this->reminder);
        }
        
        if ($this->reminderView->userWantsToViewReminders()) {
            $this->reminderView->displayReminderList();
        }
    }

    public function getReminderView() {
        return $this->reminderView;
    }
}