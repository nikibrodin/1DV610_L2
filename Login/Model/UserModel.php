<?php

class UserModel {

    private $username;
    private $password;
    private static $minLengthUserName = 3;
    private static $minLengthPassword = 6;

    public function __construct(string $username, string $password) {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    private function setUsername(string $username)  {
        if (strlen($username) >= self::$minLengthUserName) {
            $this->username = $username;
        } else {
            throw new Exception("Invalid username");
        }
	}

    public function getUsername() : string { return $this->username; }

    private function setPassword(string $password)  {
		if (strlen($password) >= self::$minLengthPassword) {
            $this->password = $password;
        } else {
            throw new Exception("Invalid password");
        }
    }
    
    public function getPassword() : string { return $this->password; }

    public function getMinLengthUsername(): int { return self::$minLengthUserName; }
    public function getMinLengthPassword(): int { return self::$minLengthPassword; }
}