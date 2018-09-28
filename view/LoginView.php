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
	private static $savedRegisterName = '';
	private $message = '';
	private $response;

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateLoginFormHTML($this->message);
		}

		// USER STORAGE MODEL DEPENDENCY
		$userStorage = new UserStorageModel();

		if (isset($_POST[self::$logout])){

			$this->response = $this->generateLoginFormHTML($this->message);

		} 
		if ($userStorage->isSet()) {
			$this->message = '';
			$this->response = $this->generateLogoutButtonHTML($this->message);
		}

		// ---------------------------------THIS IS THE PROBLEM---------------------------------DO NOT HARD CORE USE SELF::$REGISTER
		/*if (isset($_GET['register'])) {
			echo "get register";
			$this->message = '';
			$this->response = $this->generateRegisterFormHTML($this->message);
		}*/
		
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

	// SET FLASH MESSAGE
	public function setMessage($message) {
		$this->message = $message;
	}

	//CHECKS IF USERNAME AND PASSWORD IS SET.
	public function userWantsToLogin() : bool {
		// USER STORAGE MODEL DEPENDENCY
		$userStorage = new UserStorageModel();
		$dataBase = new DataBaseModel();
		$user = new UserModel();
		
		$bool = false;

		// IF LOGIN
		if (isset($_POST[self::$login])) {
			$bool = true;
			$user->setUsername(trim($_POST[self::$name]));
			$user->setPassword(trim($_POST[self::$password]));
			// SET SAVED NAME
			self::$savedName = trim($_POST[self::$name]);

			if (!$dataBase->isAuthenticated($user)) {
				$this->message = 'Wrong name or password';
				$bool = false;
			}
			if (isset($_POST[self::$password]) && $_POST[self::$password] == "") {
				$this->message = 'Password is missing';
				$bool = false;
			}
	
			if (isset($_POST[self::$name]) && $_POST[self::$name] == "") {
			   $this->message = 'Username is missing';
			   $bool = false;
			}
			
			$this->response = $this->generateLoginFormHTML($this->message);

			/*if ($userStorage->isSet()) {
				$this->message = '';
				$this->response = $this->generateLogoutButtonHTML($this->message);
				$bool = false;
			}*/

		}

		return $bool;
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : UserModel {
		
		$user = new UserModel();
		
		//RETURN REQUEST VARIABLE: USERNAME
		$rawUsername = $_POST[self::$name];
		$filteredUsername = trim($rawUsername);

		//RETURN REQUEST VARIABLE: PASSWORD
		$rawPassword = $_POST[self::$password];
		$filteredPassword = trim($rawPassword);

		
		$user->setUsername($filteredUsername);
		$user->setPassword($filteredPassword);


		$this->message = 'Welcome';
		$this->response = $this->generateLogoutButtonHTML($this->message);
		//RETURNS USERMODEL OBJECT
		return $user;
	}

	public function userWantsToLoginWithCookies() : bool {
		$dataBase = new DataBaseModel();
		$bool = false;
		// SHOULD RETURN A MODEL OBJECT (USER)
		if (isset($_COOKIE[self::$cookieName])) {
			$this->user = $this->getCookies();
			// echo "with cookie 1";
			// AUTHENTICATE USER
			if ($dataBase->userExists($this->user)) {
				//echo "with cookie 2";
				$this->message = 'Welcome back with cookie';
				$bool = true;
			} else {
				$this->message = 'Wrong information in cookies';
				$bool = false;
			}
		}
		return $bool;
	}

	//CHECKS IF WANT TO LOGOUT.
	public function userWantsToLogout() : bool {
		$userStorage = new UserStorageModel();
		$bool = false;
		if (isset($_POST[self::$logout])) {
			$bool = true;
			$this->message = 'Bye bye!';
			if (!$userStorage->isSet()) {
				$this->message = '';
			}
		}
		return $bool;	
	}

	public function userWantsRegisterForm() {
		return isset($_GET['register']);
	}

	public function displayRegisterForm() {
		// echo "display register";
		$this->message = '';
		$this->response = $this->generateRegisterFormHTML($this->message);
	}

	public function userWantsToRegister() : bool {

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

			//SET USERNAME
			$rawUsername = $_POST[self::$registerName];
			$filteredUsername = trim($rawUsername);
			self::$savedRegisterName = $filteredUsername;

			// $this->response = $this->generateRegisterFormHTML($this->message);

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

	// Probably unneccessary. 
	public function backToLogin() {
		$this->message = "";
		$this->response = $this->generateLoginFormHTML($this->message);
	}
	
	

	private function getCookies() : UserModel {
		$user = new UserModel();
		if (isset($_COOKIE[self::$cookieName])) {
			$user->setUsername($_COOKIE[self::$cookieName]);
		}
		if (isset($_COOKIE[self::$cookiePassword])) {
			$user->setPassword($_COOKIE[self::$cookiePassword]);
		}
		return $user;

	}

	//CHECKS IF USER WANTS TO KEEP BEING LOGGED IN.
	public function keepLoggedIn() : bool {
		return isset($_POST[self::$keep]);
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