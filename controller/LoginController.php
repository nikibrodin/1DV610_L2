<?php

class LoginController {
    private $loginView;
    private $user;
    // private $dataBase;
    private $userStorage;

    // private $layoutView;
    // private $dateTimeView;

    // private $registerView;

    // private $reminder;
    // private $reminderView;
    // private $reminderDBModel;

    private $authenticated = false;

    public function __construct (LoginView $loginView, UserStorageModel $userStorage/*, RegisterView $registerView, DataBaseModel $dataBase,  LayoutView $layoutView, ReminderView $reminderView, ReminderDBModel $reminderDBModel*/) {
        $this->loginView = $loginView;
        // $this->dataBase = $dataBase;
        $this->userStorage = $userStorage;
        // $this->layoutView = $layoutView;
        // $this->registerView = $registerView;
        // $this->reminderView = $reminderView;
        // $this->reminderDBModel = $reminderDBModel;

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
            // $this->view = $this->loginView;
        }

        //THE USER HAS COOKIES
        if ($this->loginView->userWantsToLoginWithCookies()) {
            $this->authenticated = true;
            // $this->view = $this->loginView;
        } 

        if ($this->loginView->userWantsToLogout()) {
            $this->loginView->removeCookies();
            $this->userStorage->clear();
            // $this->view = $this->loginView;
        } 
        // IF SESSION
        if ($this->userStorage->isSet()) {
            $this->authenticated = true;
            // $this->view = $this->loginView;
        }
        // if ($this->loginView->userWantsRegisterForm()) {
        //     $this->view = $this->registerView;
        // }

        // if ($this->registerView->userWantsLoginForm()) {
        //     $this->view = $this->loginView;
        // }

        // if ($this->registerView->userWantsToRegister()) {
        //     // SHOULD RETURN A MODEL OBJECT (USER)
        //     $this->user = $this->registerView->getRegisteredUser();
        //     $this->dataBase->addUser($this->user);
        // }
        // if ($this->reminderView->userWantsCreateForm()) {}
            
        // if ($this->reminderView->userWantsToCreateReminder()) {
        //     $this->reminder = $this->reminderView->getReminder();
        //     $this->reminderDBModel->createReminder($this->reminder);
            
        // }

        // $this->layoutView->render($this->authenticated, $this->view, $this->reminderView);
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