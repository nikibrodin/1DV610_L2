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

            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRequestUserName();

            // AUTHENTICATE USER
            if ($this->dataBase->isAuthenticated($this->user)) {

                return true;
            
            } else {

                return false;

            }
            

        } else {

            return false;

        }
    }
}