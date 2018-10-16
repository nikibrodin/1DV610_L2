<?php

class DataBaseModel {

    private static $path = "./database.xml";
    private static $username = "username";
    private static $password = "password";
    private static $user = "user";

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated (UserModel $user) : bool {

        $userName = $user->getUsername();
        $password = $user->getPassword();

        $xml = simplexml_load_file(self::$path);

        foreach($xml->children() as $child) {
            // VERIFY HASHED STRING FOR PASSWORD
            if ($child[self::$username] == $userName && password_verify($password, $child[self::$password])) {
                return true;
            }
        }

        return false;

    }

    public function userExists(UserModel $user) : bool {
        $userName = $user->getUsername();

        $xml = simplexml_load_file(self::$path);

        foreach($xml->children() as $child) {
            if ($child[self::$username] == $userName) {
                return true;
            }
        }

        return false;
    }

    public function addUser(UserModel $user) {

        $userName = $user->getUsername();
        $hash = password_hash($user->getPassword(), PASSWORD_DEFAULT);

        $xml = simplexml_load_file(self::$path);

        $user = $xml->addChild(self::$user);
        $user->addAttribute(self::$username, $userName);
        $user->addAttribute(self::$password, $hash);

        $xml->asXml(self::$path);
    }

    public function usernameExists(string $username) {

        $xml = simplexml_load_file(self::$path);

        if ($xml->count() == 0) {
            return false;
        }
        else {
            foreach($xml->children() as $child) {
                if ($child[self::$username] == $username) {
                    return true;
                }
            }
        }


    }
}