<?php

class LoginView extends View {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
	private static $registerLink = 'register';

	private $userStorage;
	private $dataBase;
	private $user;

	private static $savedName = '';
	private $message = '';
	private $response;

	public function __construct (UserStorageModel $userStorage, DataBaseModel $dataBase) {
		$this->userStorage = $userStorage;
		$this->dataBase = $dataBase;
    }

	public function response() {
		if (empty($this->response)) {
			$this->response = $this->generateLoginFormHTML($this->message);
		}

		if ($this->userStorage->isSet()) {
			$this->response = $this->generateLogoutButtonHTML($this->message);
		}
		
		return $this->response;
	}

	public function generateRegister() {

		return '
			<a href="?' . self::$registerLink . '">Register a new user</a>
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

	public function userWantsToLogin() : bool {		
		// IF LOGIN
		if (isset($_POST[self::$login])) {
			$username = trim($_POST[self::$name]);
			self::$savedName = $username;
			$password = trim($_POST[self::$password]);

			try {
				$this->user = new UserModel($username, $password);
			} catch (Exception $e) {
				if (empty($password)) {
					$this->message = 'Password is missing';
					return false;
				}
		
				if (empty($username)) {
				   $this->message = 'Username is missing';
				   return false;
				}
			}

			if (!$this->dataBase->isAuthenticated($this->user)) {
				$this->message = 'Wrong name or password';
				return false;
			}

			$this->response = $this->generateLoginFormHTML($this->message);

			if ($this->userStorage->isSet()) {
				$this->message = '';
				$this->response = $this->generateLogoutButtonHTML($this->message);
				return false;
			}
			return true;
		}
		return false;
	}

	//CREATE GET-FUNCTIONS TO FETCH REQUEST VARIABLES
	public function getRequestUserName() : UserModel {

		$this->message = 'Welcome';
		$this->response = $this->generateLogoutButtonHTML($this->message);
	
		//RETURNS USERMODEL OBJECT
		return $this->user;
	}

	public function userWantsToLoginWithCookies() : bool {
		$bool = false;
		// SHOULD RETURN A MODEL OBJECT (USER)
		if (isset($_COOKIE[self::$cookieName])) {
			$this->user = $this->getCookies();
			// AUTHENTICATE USER
			if ($this->dataBase->userExists($this->user)) {
				$this->message = 'Welcome back with cookie';
				$this->response = $this->generateLogoutButtonHTML($this->message);
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
		$bool = false;
		if (isset($_POST[self::$logout])) {
			$bool = true;
			$this->message = 'Bye bye!';
			if (!$this->userStorage->isSet()) {
				$this->message = '';
			}
		}
		return $bool;	
	}

	public function userWantsRegisterForm() {
		return isset($_GET[self::$registerLink]);
	}

	private function getCookies() : UserModel {
		if (isset($_COOKIE[self::$cookieName])) {
			$this->user = new UserModel($_COOKIE[self::$cookieName], $_COOKIE[self::$cookiePassword]);
		}
		return $this->user;

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
	}
	
}