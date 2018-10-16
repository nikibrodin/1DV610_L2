<?php

class MasterController {
    private $loginController;
    private $registerController;
    private $reminderController;

    private $layoutView;
    private $loginView;
    private $registerView;
    private $reminderView;

    private $authenticated;
    private $showRegister;

    public function __construct (LoginController $loginController, RegisterController $registerController, ReminderController $reminderController, LayoutView $layoutView) {
        $this->loginController = $loginController;
        $this->registerController = $registerController;
        $this->reminderController = $reminderController;
        $this->layoutView = $layoutView;
    }

    public function run () {

        
        $this->loginController->authentication();
        $this->LoginView = $this->loginController->getLoginView();
        if ($this->loginController->userWantsRegisterform()) { $this->showRegister = true; }
        $this->authenticated = $this->loginController->userIsAuthenticated();

        $this->registerController->registration();
        $this->registerView = $this->registerController->getRegisterView();
        if ($this->registerController->userWantsLoginform()) { $this->showRegister = false; }

        $this->reminderController->manageReminders();
        $this->reminderView = $this->reminderController->getReminderView();
    
    
        $this->layoutView->render($this->authenticated, $this->showRegister, $this->LoginView, $this->registerView, $this->reminderView);
    }
}