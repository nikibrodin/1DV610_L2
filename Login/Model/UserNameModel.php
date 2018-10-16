<?php

class UserNameModel {

    private $username;

    public function __construct(string $username) {
        $this->setUsername($username);
    }

    private function setUsername(string $username)  {
        $this->username = $username;
	}

    public function getUsername() : string { return $this->username; }

}