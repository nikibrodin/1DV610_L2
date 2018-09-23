<?php

// require_once('model/UserStorageModel.php'); //NEW

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	private static $register = 'RegisterView::Register';
	private static $registerName = 'RegisterView::UserName';
	private static $registerPassword = 'RegisterView::Password';
	private static $registerPasswordRepeat = 'RegisterView::PasswordRepeat';
	private static $messageRegister = 'RegisterView::Message';

	private static $savedName = '';
	private $message = '';
	private $response;

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateLoginFormHTML($this->message);
		}

		// USER STORAGE MODEL DEPENDENCY
		$userStorage = new UserStorageModel();

		// IF LOGIN
		if (isset($_POST[self::$login])) {

			if (isset($_POST[self::$password]) && isset($_POST[self::$name])) {
				$this->response = $this->generateLoginFormHTML($this->message);
			}
			   
			if (isset($_POST[self::$password]) && $_POST[self::$password] == "") {
				$this->message = 'Password is missing';
				$this->response = $this->generateLoginFormHTML($this->message);
			   }
	
			if (isset($_POST[self::$name]) && $_POST[self::$name] == "") {
			   $this->message = 'Username is missing';
			   $this->response = $this->generateLoginFormHTML($this->message);
			}

			if ($userStorage->isSet()) {
				$this->response = $this->generateLogoutButtonHTML($this->message);
			}

			if ($this->isLoggedIn()) {
				$this->message = 'Welcome back with cookie';
				$this->response = $this->generateLogoutButtonHTML($this->message);
			}
		} else if (isset($_POST[self::$logout])){

			//$this->message = 'Bye bye!';
			$this->response = $this->generateLoginFormHTML($this->message);

		} else if ($userStorage->isSet()) {
			$this->message = '';
			$this->response = $this->generateLogoutButtonHTML($this->message);
		}
		if (isset($_GET['register'])) {
			$this->message = '';
			$this->response = $this->generateRegisterFormHTML($this->message);
		}
		if (isset($_POST[self::$register])) {
			if (strlen($_POST[self::$registerName]) < 4) {
				$this->message = 'Username has too few characters, at least 3 characters.<br>';
			}
	
			if (strlen($_POST[self::$registerPassword]) < 7) {
				$this->message .= 'Password has too few characters, at least 6 characters.<br>';
			}

			if ($_POST[self::$registerPassword] != $_POST[self::$registerPasswordRepeat]) {
				$this->message .= 'Passwords do not match.<br>';
			}

		 	$this->response = $this->generateRegisterFormHTML($this->message);
		}
		//if ($this->isLoggedIn()) {
		//	$this->message = 'Welcome back with cookie';
		//	$this->response = $this->generateLogoutButtonHTML($this->message);
		//}
		
		return $this->response;
	}

	public function generateRegister() {

		if (empty($_GET)) {
		return '
			<a href="?register">Register a new user</a>
		';
		} else if (isset($_GET['register'])){
		return '
			<a href="?">Back to login</a>
		';
		}
	}

	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	private function generateLoginFormHTML($message) {
		return '
			<form method="post" > 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::$savedName . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	private function generateRegisterFormHTML($message) {
		return '
			<form action="?register" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>Register a new user - Write username and password</legend>
					<p id="' . self::$messageRegister . '">' . $message . '</p>
					
					<label for="' . self::$registerName . '">Username :</label>
					<input type="text" size="20" name="' . self::$registerName . '" id="' . self::$registerName . '" value="">
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

	// SET FLASH MESSAGE
	public function setMessage($message) {
		$this->message = $message;
	}

	//CHECKS IF USERNAME AND PASSWORD IS SET.
	public function userWantsToLogin() : bool {
		return isset($_POST[self::$name]) && isset($_POST[self::$password]) || isset($_COOKIE[self::$cookieName]);
	}

	//CHECKS IF WANT TO LOGOUT.
	public function userWantsToLogout() : bool {
		return isset($_POST[self::$logout]);	
	}

	public function userWantsToRegister() : bool {

		if (isset($_POST[self::$register])) {
			//RETURN REQUEST VARIABLE: REGISTER USERNAME
			$rawUsername = $_POST[self::$registerName];
			$filteredUsername = trim($rawUsername);

			//RETURN REQUEST VARIABLE: REGISTER PASSWORD
			$rawPassword = $_POST[self::$registerPassword];
			$filteredPassword = trim($rawPassword);

			if (strlen($filteredUsername) > 3 && strlen($filteredPassword) > 6) {
				return true;
			}
			return false;
		}

		return false;
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : UserModel {

		$user = new UserModel();
		if (isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])) {
			$user->setUsername($_COOKIE[self::$cookieName]);
			$user->setPassword($_COOKIE[self::$cookiePassword]);
		} else {
			//RETURN REQUEST VARIABLE: USERNAME
			$rawUsername = $_POST[self::$name];
			$filteredUsername = trim($rawUsername);

			// SET SAVED NAME
			self::$savedName = $filteredUsername;


			//RETURN REQUEST VARIABLE: PASSWORD
			$rawPassword = $_POST[self::$password];
			$filteredPassword = trim($rawPassword);

		
			$user->setUsername($filteredUsername);
			$user->setPassword($filteredPassword);
		}

		//RETURNS USERMODEL OBJECT
		return $user;
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getNewUser() : UserModel {

		//RETURN REQUEST VARIABLE: USERNAME
		$rawUsername = $_POST[self::$name];
		$filteredUsername = trim($rawUsername);

		// SET SAVED NAME
		self::$savedName = $filteredUsername;

		//RETURN REQUEST VARIABLE: PASSWORD
		$rawPassword = $_POST[self::$password];
		$filteredPassword = trim($rawPassword);

		$user = new UserModel();
		$user->setUsername($filteredUsername);
		$user->setPassword($filteredPassword);

		//RETURNS USERMODEL OBJECT
		return $user;
	}

	//CHECKS IF USER WANTS TO KEEP BEING LOGGED IN.
	public function keepLoggedIn() : bool {
		return isset($_POST[self::$keep]);
	}

	public function isLoggedIn() : bool {
		return isset($_COOKIE[self::$cookieName]);
	}
	
	//SET COOKIES
	public function setCookies(UserModel $user) {
		$this->setCookieName($user->getUsername());
		$this->setCookiePassword($user->getPassword());
	}

	public function removeCookies() {
		if (isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])) {
			setcookie(self::$cookieName, "", time()-3600);
			setcookie(self::$cookiePassword, "", time()-3600);
		}
	}

	// GET COOKIE
	public function getCookieName() {
		if (isset($_COOKIE[self::$cookieName])) {
			return $_COOKIE[self::$cookieName];
		} else {
			return;
		}
	}

	// SET COOKIE
    public function setCookieName($name) {
		setcookie(self::$cookieName, $name);
		// $_COOKIE[self::$cookieName] = $name;
	}
	
	// GET COOKIE PASSWORD
	public function getCookiePassword() {
		if (isset($_COOKIE[self::$cookiePassword])) {
			return $_COOKIE[self::$cookiePassword];
		} else {
			return;
		}
	}

	// SET COOKIE
    public function setCookiePassword($password) {
		setcookie(self::$cookiePassword, $password);
		// $_COOKIE[self::$cookiePassword] = $password;
	}
	
}