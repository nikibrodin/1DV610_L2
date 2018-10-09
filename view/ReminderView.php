<?php

class ReminderView {
	private static $reminder = 'ReminderView::Reminder';
	private static $create = 'ReminderView::Create';
	private static $read = 'ReminderView::Read';
	private static $update = 'ReminderView::Update';
	private static $delete = 'ReminderView::Delete';
	private static $form = 'ReminderView::Form';

	private $response;

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateCreateButtonHTML();
		}
		if (isset($_POST[self::$form])) {
			$this->response = $this->generateCreateFormHTML();
		}
		if (isset($_POST[self::$create])) {
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
		' . $this->generateReminderList() . '
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
		$dataBase = new ReminderDBModel();
		$reminders = $dataBase->getAllReminders();

		$li = "";
		foreach ($reminders as $reminder) {
			$li .= '<li>' . $reminder . '</li>';
		}
		return '
			<div style="max-height:200px; overflow:auto">
				<ul>
					' . $li . '
				</ul>
			</div>';
	}

	public function userWantsCreateForm() : bool {
		return isset($_POST[self::$form]);
	}

	public function userWantsToCreateReminder() : bool {
		return isset($_POST[self::$create]);
	}

	public function getReminder () : ReminderModel {

		$reminder = new ReminderModel();
	
		$rawReminder = $_POST[self::$reminder];
		$filteredReminder = trim($rawReminder);

		
		$reminder->setReminder($filteredReminder);
	
		//RETURNS REMINDER MODEL OBJECT
		return $reminder;
	}
	
}

