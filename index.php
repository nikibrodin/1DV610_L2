<?php

//Master Module
require_once('Master/Controller/MasterController.php');
require_once('Master/View/DateTimeView.php');
require_once('Master/View/LayoutView.php');

//Login Module
require_once('Login/Controller/LoginController.php');
require_once('Login/View/LoginView.php');
require_once('Login/Model/UserModel.php');
require_once('Login/Model/DataBaseModel.php');
require_once('Login/Model/UserStorageModel.php');

//Registration Module
require_once('Register/Controller/RegisterController.php');
require_once('Register/View/RegisterView.php');

//Reminder Module
require_once('Reminder/Controller/ReminderController.php');
require_once('Reminder/View/ReminderView.php');
require_once('Reminder/Model/ReminderDBModel.php');
require_once('Reminder/Model/ReminderModel.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

$reminderDBModel = new ReminderDBModel();
$dataBase = new DataBaseModel();
$userStorage = new UserStorageModel();

$loginView = new LoginView($userStorage, $dataBase);
$registerView = new RegisterView($dataBase);
$reminderView = new ReminderView($reminderDBModel);
$dateTimeView = new DateTimeView();
$layoutView = new LayoutView($dateTimeView);


$loginController = new LoginController($loginView, $userStorage);
$registerController = new RegisterController($registerView, $dataBase);
$reminderController = new ReminderController($reminderView, $reminderDBModel);
$masterController = new MasterController($loginController, $registerController, $reminderController, $layoutView);


$masterController->run();




