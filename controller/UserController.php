<?php

class UserController {
    private $view;
    private $user;


    public function __construct (LoginView $view) {
        $this->view = $view;
    }

    public function authenticateUser() {

        //THE USER HAS PUT USERNAME AND PASSWORD
        if ($this->view->userWantsToLogin()) {

            //SHOULD RETURN A MODEL OBJECT
            $this->user = $this->view->getRequestUserName();

            //ONLY WORKS WITH USERNAME: Niki
            if ($this->user->isAuthenticated()) {

                //$this->view->response();
                echo 'User was -found!';
            
            } else {

                throw new Exception("THE USER WAS NOT FOUND");

            }
            

        } else {

            throw new Exception("Not implemented");

        }
    }
}