<?php

session_start();

class UserStorageModel {

    private static $SESSION_KEY = __CLASS__ . "user";

    public function isSet() {
		if (isset($_SESSION[self::$SESSION_KEY])) {
			return true;
		} else {
			return false;
		}
	}

    public function saveUser(UserModel $user) {
        $_SESSION[self::$SESSION_KEY] = $user;
    }

    public function getSessID() {
        return session_id();
    }

    public function clear() {
        unset($_SESSION[self::$SESSION_KEY]);
        session_destroy();
    }
}
