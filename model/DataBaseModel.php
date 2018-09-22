<?php

class DataBaseModel {

    private $myUsername = "Niki";

    //EMPTY CONSTRUCTOR, MAY NOT BE NEEDED.
    public function __construct () {

    }

    //SHOULD IMPLEMENT A DATABASE.
    public function isAuthenticated(UserModel $user) : bool {
        
        // $fileResource = fopen($fileName, 'r');
        // $fileSize = filesize($fileName);
        // $fileText = fread($fileResource, $fileSize);
        // fclose($fileResource);
        // echo $fileText;
        $userName = $user->getUsername();
        $passWord = $user->getPassword();
        

        
        $fileName = "./database.txt";
        $lines = file ($fileName);

        $bool = false;
        foreach ($lines as $line) {
            echo $line;
            if ($line == $userName . $passWord) {
                $bool = true;
            };
        }
        echo $userName . $passWord;

        return $bool;

    }
}