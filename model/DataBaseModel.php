<?php

class DataBaseModel {

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated(UserModel $user) : bool {

        return $this->username == $this->myUsername;

    }

}