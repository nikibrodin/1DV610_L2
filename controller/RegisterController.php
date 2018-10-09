<?php

class RegisterController {
    private $dataBase;
    private $registerView;

    public function __construct (RegisterView $registerView, DataBaseModel $dataBase) {
        $this->registerView = $registerView;
        $this->dataBase = $dataBase;
    }

    public function registration() {

        if ($this->registerView->userWantsToRegister()) {
            // SHOULD RETURN A MODEL OBJECT (USER)
            $this->user = $this->registerView->getRegisteredUser();
            $this->dataBase->addUser($this->user);
        }
    }

    public function userWantsLoginForm() {
        return $this->registerView->userWantsLoginForm();
    }

    public function getRegisterView() {
        return $this->registerView;
    }
}