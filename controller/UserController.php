<?php

class UserController {
    private $loginView;
    private $user;
    private $dataBase;
    private $userStorage;


    public function __construct (LoginView $loginView, DataBaseModel $dataBase, UserStorageModel $userStorage) {
        $this->loginView = $loginView;
        $this->dataBase = $dataBase;
        $this->userStorage = $userStorage;

    }

    public function authenticateUser() {

        //THE USER HAS PUT USERNAME AND PASSWORD
        if ($this->loginView->userWantsToLogin()) {

            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRequestUserName();

            // AUTHENTICATE USER
            if ($this->dataBase->isAuthenticated($this->user)) {
                $this->userStorage->saveUser($this->user);
                return true;
            } else {
                return false;
            }
        } else if ($this->loginView->userWantsToLogout()) {
            // echo "Trying to logout.";
            $this->userStorage->clear();
            return false;
        }
    }
}