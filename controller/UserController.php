<?php

class UserController {
    private $loginView;
    private $user;
    private $dataBase;


    public function __construct (LoginView $loginView, DataBaseModel $dataBase) {
        $this->loginView = $loginView;
        $this->dataBase = $dataBase;

    }

    public function authenticateUser() {

        //THE USER HAS PUT USERNAME AND PASSWORD
        if ($this->loginView->userWantsToLogin()) {

            //SHOULD RETURN A MODEL OBJECT
            $this->user = $this->loginView->getRequestUserName();

            //ONLY WORKS WITH: nikiniki
            if ($this->dataBase->isAuthenticated($this->user)) {
                echo "Logged in.";
                
                return true;
            
            } else {

                return false;
                // throw new Exception("THE USER WAS NOT FOUND");

            }
            

        } else {

            throw new Exception("Not implemented");

        }
    }
}