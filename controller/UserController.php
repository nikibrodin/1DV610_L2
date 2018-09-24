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

            // AUTHENTICATE USER
            if ($this->dataBase->isAuthenticated($this->user)) {
                if ($this->userStorage->isSet()) {
                    $this->loginView->setMessage('');
                    $this->authenticated = true;
                } else {
                    $this->userStorage->saveUser($this->user);
                    $this->loginView->setMessage('Welcome');
                    if ($this->loginView->keepLoggedIn()) {
                        $this->loginView->setCookies($this->user);
                    }
                    $this->authenticated = true;
                }
            } else {
                $this->loginView->setMessage('Wrong name or password');
                $this->authenticated = false;
            }
        
        } 

        //THE USER HAS COOKIES
        if ($this->loginView->userWantsToLoginWithCookies()) {

            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getCookies();

            // AUTHENTICATE USER
            if ($this->dataBase->userExists($this->user)) {
                // $this->userStorage->saveUser($this->user);
                $this->loginView->setMessage('Welcome back with cookie');
                $this->authenticated = true;
            
            } else {
                $this->loginView->setMessage('Wrong name or password');
                $this->authenticated = false;
            }
        
        } 

        if ($this->loginView->userWantsToLogout()) {
            if (!$this->userStorage->isSet()) {
                $this->loginView->setMessage('');
                $this->authenticated = false;
            } else {
                $this->loginView->setMessage('Bye bye!');
                $this->loginView->removeCookies();
                $this->userStorage->clear();
                $this->authenticated = false;
            }
            
        } 
        // IF SESSION
        if ($this->userStorage->isSet()) {
            $this->authenticated = true;
        }
        if ($this->loginView->userWantsToRegister()) {
            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->loginView->getRegisteredUser();
            $this->dataBase->addUser($this->user);
        }

        $this->layoutView->render($this->authenticated, $this->loginView, $this->dateTimeView);
    }
}