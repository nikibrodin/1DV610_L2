<?php

class UserController {
    private $loginView;
    private $user;
    private $dataBase;
    private $userStorage;
    private $layoutView;
    private $dateTimeView;
    private $authenticated = false;

    public function __construct (LoginView $loginView, DataBaseModel $dataBase, UserStorageModel $userStorage, LayoutView $layoutView, DateTimeView $dateTimeView) {
        $this->loginView = $loginView;
        $this->dataBase = $dataBase;
        $this->userStorage = $userStorage;
        $this->layoutView = $layoutView;
        $this->dateTimeView = $dateTimeView;

    }

    public function authentication() {

        //THE USER HAS PUT USERNAME AND PASSWORD
        if ($this->loginView->userWantsToLogin()) {

            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRequestUserName();

            // SAVE USER
            $this->userStorage->saveUser($this->user);
            if ($this->loginView->keepLoggedIn()) {
                $this->loginView->setCookies($this->user);
            }
            $this->authenticated = true;
        }

        //THE USER HAS COOKIES
        if ($this->loginView->userWantsToLoginWithCookies()) {
            $this->authenticated = true;
        } 

        if ($this->loginView->userWantsToLogout()) {
            $this->loginView->removeCookies();
            $this->userStorage->clear();
        } 
        // IF SESSION
        if ($this->userStorage->isSet()) {
            $this->authenticated = true;
        }
        if ($this->loginView->userWantsRegisterForm()) {
            $this->loginView->displayRegisterForm();
        }

        if ($this->loginView->userWantsToRegister()) {
            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRegisteredUser();
            $this->dataBase->addUser($this->user);
        }

        $this->layoutView->render($this->authenticated, $this->loginView, $this->dateTimeView);
    }
}