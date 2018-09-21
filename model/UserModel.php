<?php

class UserModel {

    private $username;
    private $password;
    private $myUsername = "Niki";

    public function __construct ($username, $password) {

        $this->username = $username;
        $this->password = $password;

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated() : bool {

        return $this->username == $this->myUsername;

    }

}