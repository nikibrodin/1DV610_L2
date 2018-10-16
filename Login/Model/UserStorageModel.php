<?php

session_start();

class UserStorageModel {

    private static $sessionKey = 'sessionKey::User';

    public function isSet() {
		if (isset($_SESSION[self::$sessionKey])) {
			return true;
		} else {
			return false;
		}
	}

    public function saveUser(UserModel $user) {
        $_SESSION[self::$sessionKey] = $user;
    }

    public function getSessID() {
        return session_id();
    }

    public function clear() {
        unset($_SESSION[self::$sessionKey]);
        session_destroy();
    }
}
