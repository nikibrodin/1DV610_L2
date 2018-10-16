<?php

class RegisterView {

	private static $register = 'RegisterView::Register';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $registerPasswordRepeat = 'RegisterView::PasswordRepeat';
    private static $messageRegister = 'RegisterView::Message';
    
    private $dataBase;
    private $user;

	private static $savedRegisterName = '';
	private $message = '';
	private $response;
	private $validated = false;
    
    public function __construct (DataBaseModel $dataBase) {
		$this->dataBase = $dataBase;
    }

	public function response() {
		$this->response = $this->generateRegisterFormHTML($this->message);
		
		return $this->response;
	}

	public function generateRegister() {
		return '
			<a href="?">Back to login</a>
		';
	}

	private function generateRegisterFormHTML($message) {
		return '
			<form action="?register" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageRegister . '">' . $message . '</p>
					
					<label for="' . self::$registerName . '">Username :</label>
					<input type="text" size="20" name="' . self::$registerName . '" id="' . self::$registerName . '" value="' . self::$savedRegisterName . '">
					<br>
					
					<label for="' . self::$registerPassword . '">Password  :</label>
					<input type="password" size="20" name="' . self::$registerPassword . '" id="' . self::$registerPassword . '" value="">
					<br>
			
					<label for="' . self::$registerPasswordRepeat . '">Repeat password  :</label>
					<input type="password" size="20" name="' . self::$registerPasswordRepeat . '" id="' . self::$registerPasswordRepeat . '" value="">
					<br>
			
					<input id="submit" type="submit" name="' . self::$register . '" value="Register">
					<br>
				</fieldset>
			</form>
		';
    }
    
    public function userWantsLoginForm() : bool {
        return $this->validated;
    }

	public function userWantsToRegister() : bool {
		return isset($_POST[self::$register]);
    }
    
    public function validInformation() {
		if (isset($_POST[self::$register])) {
			$this->validated = true;
			$filteredUsername = trim($_POST[self::$registerName]);
			$filteredPassword = trim($_POST[self::$registerPassword]);
			$filteredPasswordRepeat = trim($_POST[self::$registerPasswordRepeat]);
			//SET SAVED USERNAME
			$toSave = strip_tags($filteredUsername);;
			self::$savedRegisterName = $toSave;

			$username = new UserNameModel($filteredUsername);

			if ($filteredPassword != $filteredPasswordRepeat) {
				$this->message .= 'Passwords do not match.<br>';
				$this->validated = false;
			}
			
            if ($this->dataBase->usernameExists($username)) {
				$this->message .= 'User exists, pick another username.<br>';
                $this->validated = false;
			}

			if (preg_match("/[^A-Za-z0-9]/", $filteredUsername)) {
				$this->message .= 'Username contains invalid characters.<br>';
                $this->validated = false;
			}

			try {
				$this->user = new UserModel($filteredUsername, $filteredPassword);
			} catch (Exception $e) {
				if (strlen($filteredUsername) < 3) {
					$this->message .= 'Username has too few characters, at least 3 characters.<br>';
					$this->validated = false;
				}
		
				if (strlen($filteredPassword) < 6) {
					$this->message .= 'Password has too few characters, at least 6 characters.<br>';
					$this->validated = false;
				}
			}


			$this->response = $this->generateRegisterFormHTML($this->message);

			return $this->validated;
		}

		return false;
    }

	public function getRegisteredUser() : UserModel  {
		$rawUsername = $_POST[self::$registerName];
		$filteredUsername = trim($rawUsername);

		$rawPassword = $_POST[self::$registerPassword];
		$filteredPassword = trim($rawPassword);

		try {
			$this->user = new UserModel($filteredUsername, $filteredPassword);
		} catch (Exception $e) {
			
		}
		

        // REDIRECT TO MAIN PAGE
        header('Location: ?');
        //exit;
		//RETURNS USERMODEL OBJECT
		return $this->user;
	}	
	
}