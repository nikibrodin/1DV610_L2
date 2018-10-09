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
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
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
