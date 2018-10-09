<?php

//INCLUDE THE FILES NEEDED...
require_once('view/View.php');
require_once('view/LoginView.php');
require_once('view/RegisterView.php');
require_once('view/ReminderView.php');
require_once('view/DateTimeView.php');
require_once('view/LayoutView.php');

require_once('controller/MasterController.php');
require_once('controller/LoginController.php');
require_once('controller/RegisterController.php');
require_once('controller/ReminderController.php');


require_once('model/UserModel.php');
require_once('model/DataBaseModel.php');
require_once('model/UserStorageModel.php');
require_once('model/ReminderDBModel.php');
require_once('model/ReminderModel.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER
error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
//$view = new View();
$loginView = new LoginView();
$registerView = new RegisterView();

$reminderView = new ReminderView();
$reminderDBModel = new ReminderDBModel();

$dateTimeView = new DateTimeView();
$layoutView = new LayoutView($dateTimeView);
$dataBase = new DataBaseModel();
$userStorage = new UserStorageModel();


$loginController = new LoginController($loginView, $userStorage);
$registerController = new RegisterController($registerView, $dataBase);
$reminderController = new ReminderController($reminderView, $reminderDBModel);
$masterController = new MasterController($loginController, $registerController, $reminderController, $layoutView);


$masterController->run();




