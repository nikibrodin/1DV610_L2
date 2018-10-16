<?php

class ReminderView {
	private static $reminder = 'ReminderView::Reminder';
	private static $create = 'ReminderView::Create';
	private static $delete = 'ReminderView::Delete';
	private static $form = 'ReminderView::Form';

	private $dataBase;
	private $reminderModel;

	private $response;

	public function __construct (ReminderDBModel $dataBase) {
        $this->dataBase = $dataBase;
	}

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateCreateButtonHTML();
		}
		
		return $this->response;
	}

	public function generateCreateButtonHTML() {
		return '
		<h2>Reminders</h2>
		' . $this->generateReminderList() . '
		<form  method="post" >
			<input type="submit" name="' . self::$form . '" value="Create reminder"/>
		</form>
		';
	}

	public function generateCreateFormHTML() {
		return '
		<h2>Reminders</h2>
		<form method="post" >
			<fieldset>
				<legend>Create Reminder</legend>
				
				<label for="' . self::$reminder . '">Reminder :</label>
				<input type="text" id="' . self::$reminder . '" name="' . self::$reminder . '" value="" />
				
				<input type="submit" name="' . self::$create . '" value="create" />
			</fieldset>
		</form>
		';
	}

	private function generateReminderList() : string {
		$reminders = $this->dataBase->getAllReminders();

		$li = "";
		foreach ($reminders as $reminder) {
			$li .= '<form  method="post" ><input type="submit" name="' . self::$delete . '" value="' . $reminder . '"/></form>';
		}
		return '
			<p>Click on reminder to remove from list!</p>
			<div style="max-height:200px; overflow:auto">
				<ul>
					' . $li . '
				</ul>
			</div>';
	}

	public function userWantsCreateForm() : bool {
		return isset($_POST[self::$form]);
	}

	public function userWantsToDeleteReminder() : bool {
		return isset($_POST[self::$delete]);
	}

	public function userWantsToCreateReminder() : bool {
		return isset($_POST[self::$create]);
	}

	public function displayCreateForm() : void {
		$this->response = $this->generateCreateFormHTML();
	}

	public function getReminder () {

			$rawReminder = $_POST[self::$reminder];
			$filteredReminder = trim($rawReminder);
			$this->reminderModel = new ReminderModel($filteredReminder);
	
			//RETURNS REMINDER MODEL OBJECT
			return $this->reminderModel;

	}
	public function displayReminders() {
		$this->response = $this->generateCreateButtonHTML();
	}

	public function getReminderToDelete() {
		$rawReminder = $_POST[self::$delete];
		$filteredReminder = trim($rawReminder);
		$this->reminderModel = new ReminderModel($filteredReminder);
	
		//RETURNS REMINDER MODEL OBJECT
		return $this->reminderModel;
	}	
}

