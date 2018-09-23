<?php

//INCLUDE THE FILES NEEDED...
require_once('view/LoginView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');
require_once('controller/UserController.php'); //NEW
require_once('model/UserModel.php'); //NEW
require_once('model/DataBaseModel.php'); //NEW
require_once('model/UserStorageModel.php'); //NEW

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$loginView = new LoginView();
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView();
$dataBase = new DataBaseModel(); //NEW
$userStorage = new UserStorageModel(); //NEW
$userController = new UserController($loginView, $dataBase, $userStorage, $layoutView, $dateTimeView); //NEW

// Controller authenticates user.
$userController->authentication();
//False -> not logged in.
// $layoutView->render($authenticated, $loginView, $dateTimeView);




