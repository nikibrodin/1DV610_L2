<?php

class DataBaseModel {

    private $myUsername = "Niki";

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated (UserModel $user) : bool {

        $userName = $user->getUsername();
        $password = $user->getPassword();

        $bool = false;

        $xml = simplexml_load_file("./database.xml");

        foreach($xml->children() as $child) {
            if ($child["username"] == $userName && $child["password"] == $password) {
                $bool = true;
            }
        }

        return $bool;

    }
}