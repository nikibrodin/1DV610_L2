<?php

class RegisterView extends View {

	private static $register = 'RegisterView::Register';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $registerPasswordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageRegister = 'RegisterView::Message';

	private static $savedRegisterName = '';
	private $message = '';
	private $response;

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
        return empty($_GET);
    }

	public function userWantsToRegister() : bool {
        $dataBase = new DataBaseModel();

		if (isset($_POST[self::$register])) {
			$bool = true;

			if (strlen($_POST[self::$registerName]) < 4) {
				$this->message = 'Username has too few characters, at least 3 characters.<br>';
				$bool = false;
			}
	
			if (strlen($_POST[self::$registerPassword]) < 6) {
				$this->message .= 'Password has too few characters, at least 6 characters.<br>';
				$bool = false;
			}
	
			if ($_POST[self::$registerPassword] != $_POST[self::$registerPasswordRepeat]) {
				$this->message .= 'Passwords do not match.<br>';
				$bool = false;
            }
            try {
                $dataBase->usernameExists($_POST[self::$registerName]);
            } catch (Exception $e){
                $this->message .= 'User exists, pick another username.<br>';
                $bool = false;
            }

			//SET SAVED USERNAME
			self::$savedRegisterName = trim($_POST[self::$registerName]);

			$this->response = $this->generateRegisterFormHTML($this->message);

			return $bool;
		}

		return false;
	}

	public function getRegisteredUser() : UserModel  {
		$user = new UserModel();

		$rawUsername = $_POST[self::$registerName];
		$filteredUsername = trim($rawUsername);

		//RETURN REQUEST VARIABLE: REGISTER PASSWORD
		$rawPassword = $_POST[self::$registerPassword];
		$filteredPassword = trim($rawPassword);

		$user->setUsername($filteredUsername);
		$user->setPassword($filteredPassword);

		$this->message = '';
		$this->response = $this->generateLoginFormHTML($this->message);

		//RETURNS USERMODEL OBJECT
		return $user;
	}	
	
}