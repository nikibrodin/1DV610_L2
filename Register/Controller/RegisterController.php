<?php

class RegisterController {
    private $dataBase;
    private $registerView;
    private $user;
    private $validInformation;

    public function __construct (RegisterView $registerView, DataBaseModel $dataBase) {
        $this->registerView = $registerView;
        $this->dataBase = $dataBase;
    }

    public function registration() {
        $this->validInformation = false;

        if ($this->registerView->userWantsToRegister()) {
            // SHOULD RETURN A MODEL OBJECT (USER)
            if ($this->registerView->validRegistrationInformation()) {
                $this->user = $this->registerView->getRegisteredUser();
                $this->dataBase->addUser($this->user);
                $this->validInformation = true;
            }
        }
    }

    public function userWantsLoginForm() {
        if ($this->validInformation || $this->registerView->userWantsLoginForm()) { return true; }
    }

    public function getRegisterView() {
        return $this->registerView;
    }
}