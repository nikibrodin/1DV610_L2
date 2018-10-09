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
    private $show;

    public function __construct (LoginController $loginController, RegisterController $registerController, ReminderController $reminderController, LayoutView $layoutView) {
        $this->loginController = $loginController;
        $this->registerController = $registerController;
        $this->reminderController = $reminderController;
        $this->layoutView = $layoutView;
        $this->view = new LoginView();
    }

    public function run () {

        
        $this->loginController->authentication();
        $this->LoginView = $this->loginController->getLoginView();
        $this->show = $this->loginController->userWantsRegisterform();
        $this->authenticated = $this->loginController->userIsAuthenticated();

        $this->registerController->registration();
        $this->registerView = $this->registerController->getRegisterView();
        //$this->show = $this->registerController->userWantsLoginform();

        $this->reminderController->manageReminders();
        $this->reminderView = $this->reminderController->getReminderView();
    
    
        $this->layoutView->render($this->authenticated, $this->show, $this->LoginView, $this->registerView, $this->reminderView);
    }
}