<?php

class LoginController {
    private $loginView;
    private $user;
    private $username;
    private $userStorage;


    private $authenticated = false;

    public function __construct (LoginView $loginView, UserStorageModel $userStorage) {
        $this->loginView = $loginView;
        $this->userStorage = $userStorage;
    }

    public function authentication() {

        //THE USER HAS PUT USERNAME AND PASSWORD
        if ($this->loginView->userWantsToLogin()) {

            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRequestUserName();

            $this->userStorage->saveUser($this->user);
            if ($this->loginView->keepLoggedIn()) {
                $this->loginView->setCookies($this->user);
            }
            $this->authenticated = true;
        }

        //THE USER HAS COOKIES
        if ($this->loginView->userWantsToLoginWithCookies()) {
            if ($this->loginView->checkCookie()) {
                $this->authenticated = true;
            }
        } 

        if ($this->loginView->userWantsToLogout()) {
            $this->loginView->removeCookies();
            $this->userStorage->clear();
        } 
        // IF SESSION
        if ($this->userStorage->isSet()) {
            $this->authenticated = true;
        }

    }

    public function userWantsRegisterform() {
        return $this->loginView->userWantsRegisterForm();
    }

    public function getLoginView() {
        return $this->loginView;
    }

    public function userIsAuthenticated() : bool {
        return $this->authenticated;
    }
}