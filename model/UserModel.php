<?php

class UserModel {

    private $username;
    private $password;

    public function setUsername(string $newUserName)  {
		$this->username = $newUserName;
	}

    public function getUsername() : string {
        return $this->username;
    }

    public function setPassword(string $newPassword)  {
		$this->password = $newPassword;
    }
    
    public function getPassword() : string {
        return $this->password;
    }
}