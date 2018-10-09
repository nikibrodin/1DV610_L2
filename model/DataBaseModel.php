<?php

class DataBaseModel {

    //private $myUsername = "Niki";

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated (UserModel $user) : bool {

        $userName = $user->getUsername();
        $password = $user->getPassword();

        $xml = simplexml_load_file("./database.xml");

        foreach($xml->children() as $child) {
            if ($child["username"] == $userName && $child["password"] == $password) {
                return true;
            }
        }

        return false;

    }

    public function userExists(UserModel $user) : bool {
        $userName = $user->getUsername();

        $xml = simplexml_load_file("./database.xml");

        foreach($xml->children() as $child) {
            if ($child["username"] == $userName) {
                return true;
            }
        }

        return false;
    }

    public function addUser(UserModel $user) {

        $userName = $user->getUsername();
        $password = $user->getPassword();

        $xml = simplexml_load_file("./database.xml");

        $user = $xml->addChild('user');
        $user->addAttribute('username', $userName);
        $user->addAttribute('password', $password);

        $xml->asXml("./database.xml");
    }

    public function usernameExists(string $username) {

        $xml = simplexml_load_file("./database.xml");

        foreach($xml->children() as $child) {
            if ($child["username"] == $username) {
                throw new Exception("Username already exists");
            }
        }
    }
}