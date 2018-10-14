<?php

class LayoutView {
  private $dateTime;
  private $view;

  public function __construct (DateTimeView $dateTime) {
    $this->dateTime = $dateTime;
  }
  
  public function render($isLoggedIn, $showRegister, LoginView $loginView, RegisterView $registerView, ReminderView $reminderView) {
    if ($showRegister) {
      $this->view = $registerView;
    } else {
      $this->view = $loginView;
    }

    echo '<!DOCTYPE html>
      <html lang="en">
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
          <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
        </head>
        <body class="container">
          <h1 class="header">Assignment 2</h1>
          ' . $this->renderIsLoggedIn($isLoggedIn, $this->view, $reminderView) . '
          
          <div class="container">
              ' . $this->view->response() . '
              
              ' . $this->dateTime->show() . '
          </div>
         </body>
      </html>
    ';
  }
  
  private function renderIsLoggedIn($isLoggedIn, $view, $reminderView) {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>
      ' . $reminderView->response() . '';
    }
    else {
      return '' . $view->generateRegister() . '
      <h2>Not logged in</h2>';
    }
  }
}
