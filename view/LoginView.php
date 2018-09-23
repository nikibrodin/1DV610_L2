<?php

require_once('model/UserStorageModel.php'); //NEW

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';

	public function response() {


		self::$cookieName = $this->getCookieName();
		// echo self::$cookieName;
		self::$cookiePassword = $this->getCookiePassword();
		// USER STORAGE MODEL DEPENDENCY
		$userStorage = new UserStorageModel();

		$message = '';
		$response = $this->generateLoginFormHTML($message);

		// IF LOGIN
		if (isset($_POST[self::$login])) {

			if (isset($_POST[self::$password]) && isset($_POST[self::$name])) {
				$message = 'Wrong name or password';
				$response = $this->generateLoginFormHTML($message);
			}
			   
			if (isset($_POST[self::$password]) && $_POST[self::$password] == "") {
				$message = 'Password is missing';
				$response = $this->generateLoginFormHTML($message);
			   }
	
			if (isset($_POST[self::$name]) && $_POST[self::$name] == "") {
			   $message = 'Username is missing';
			   $response = $this->generateLoginFormHTML($message);
			}

			if ($userStorage->isSet()) {
				$message = 'Welcome';
				$response = $this->generateLogoutButtonHTML($message);
			}
		} else if (isset($_POST[self::$logout])){

			// unset($_POST[self::$logout]);
			$message = 'Bye bye!';
			$response = $this->generateLoginFormHTML($message);

		} else if ($userStorage->isSet()) {
			$message = '';
			$response = $this->generateLogoutButtonHTML($message);
		}
		
		return $response;
	}

	public function generateRegisterForm() {
		return '
			<a href="?register">Register a new user</a>
		';
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
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::$cookieName . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
		';
	}

	//CHECKS IF USERNAME IS SET.
	public function userWantsToLogin() : bool {

		return isset($_POST[self::$name]) && isset($_POST[self::$password]);
	
	}

	public function userWantsToLogout() : bool {

		return isset($_POST[self::$logout]);
	
	}
	
	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : UserModel {

		//RETURN REQUEST VARIABLE: USERNAME
		$rawUsername = $_POST[self::$name];
		$filteredUsername = trim($rawUsername);


		//RETURN REQUEST VARIABLE: PASSWORD
		$rawPassword = $_POST[self::$password];
		$filteredPassword = trim($rawPassword);

		// SET COOKIES
		$this->setCookieName($filteredUsername);
		$this->setCookiePassword($filteredPassword);

		$user = new UserModel();
		$user->setUsername($filteredUsername);
		$user->setPassword($filteredPassword);

		//RETURNS USERMODEL OBJECT
		return $user;
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
		$_COOKIE[self::$cookieName] = $name;
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
		$_COOKIE[self::$cookiePassword] = $password;
	}
	
}