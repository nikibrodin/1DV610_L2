<?php

session_start();

class UserStorage {

    private static $SESSION_KEY = __CLASS__ . "user";

    public function loadUser() {
		if (isset($_SESSION[self::$SESSION_KEY])) {
			return $_SESSION[self::$SESSION_KEY];
		} else {
			return new UserModel();
		}
	}

    public function saveUser(UserModel $toBeSaved) {
		$_SESSION[self::$SESSION_KEY] = $toBeSaved;
    }
}
