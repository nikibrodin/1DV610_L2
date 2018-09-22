<?php

session_start();

class UserStorageModel {

    private static $SESSION_KEY = __CLASS__ . "user";

    public function isSet() {
		if (isset($_SESSION[self::$SESSION_KEY])) {
            // echo "It is set.";
			return true;
		} else {
			return false;
		}
	}

    public function saveUser(UserModel $user) {
        $_SESSION[self::$SESSION_KEY] = $user;
        // echo "User saved";
    }

    public function clear() {
        unset($_SESSION[self::$SESSION_KEY]);
    }
}
