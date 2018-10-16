<?php

class ReminderView {
	private static $reminder = 'ReminderView::Reminder';
	private static $create = 'ReminderView::Create';
	private static $read = 'ReminderView::Read';
	private static $update = 'ReminderView::Update';
	private static $delete = 'ReminderView::Delete';
	private static $form = 'ReminderView::Form';
	private static $list = 'ReminderView::List';

	private $dataBase;
	private $reminderModel;
	private $message = "";

	private $response;

	public function __construct (ReminderDBModel $dataBase) {
        $this->dataBase = $dataBase;
	}

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateCreateButtonHTML($this->message);
		}
		
		return $this->response;
	}

	public function generateCreateButtonHTML(string $message) {
		return '
		<h2>Reminders</h2>
		' . $this->generateReminderList() . '
		<p>' . $message .'</p>
		<form  method="post" >
			<input type="submit" name="' . self::$form . '" value="Create reminder"/>
		</form>
		';
	}

	public function generateCreateFormHTML(string $message) {
		return '
		<h2>Reminders</h2>
		<p>' . $message .'</p>
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

	private function generateOptionsHTML(string $message) {
		return '
		<p>' . $message .'</p>
		<form  method="post" >
			<input type="submit" name="' . self::$list . '" value="Back to list"/>
		</form>
		<form  method="post" >
			<input type="submit" name="' . self::$form . '" value="Create reminder"/>
		</form>
		';
	}

	private function generateReminderList() : string {
		$reminders = $this->dataBase->getAllReminders();

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

	public function userWantsToViewReminders() : bool {
		return isset($_POST[self::$list]);
	}

	public function displayCreateForm() : void {
		$this->response = $this->generateCreateFormHTML($this->message);
	}

	public function displayReminderList() : void {
		$this->response = $this->generateCreateButtonHTML($this->message);
	}

	public function getReminder () : ReminderModel {
	
		$reminder = $_POST[self::$reminder];
		// $filteredReminder = trim($rawReminder);

		try {
			$this->reminderModel = new ReminderModel($reminder);
			$this->message = "Reminder was created";
			$this->response = $this->generateOptionsHTML($this->message);
		} catch (Exception $e) {
			$this->message = "The reminder is too long";
			$this->response = $this->generateCreateFormHTML($this->message);
		}
	
		//RETURNS REMINDER MODEL OBJECT
		return $this->reminderModel;
	}
	
}

