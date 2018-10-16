<?php

session_start();

class UserStorageModel {

    private static $SESSION_KEY_USER = __CLASS__ . "user";
    private static $SESSION_KEY_USERNAME = __CLASS__ . "username";

    public function isSet() {
		if (isset($_SESSION[self::$SESSION_KEY_USER])) {
			return true;
		} else {
			return false;
		}
	}

    public function saveUser(UserModel $user) {
        $_SESSION[self::$SESSION_KEY_USER] = $user;
    }

    public function isUsernameSet() {
		if (isset($_SESSION[self::$SESSION_KEY_USERNAME])) {
			return true;
		} else {
			return false;
		}
    }
    
    public function saveUsername(UserNameModel $username) {
        $_SESSION[self::$SESSION_KEY_USERNAME] = $username;
    }

    public function getSessID() {
        return session_id();
    }

    public function clear() {
        unset($_SESSION[self::$SESSION_KEY_USER]);
        session_destroy();
    }
}
