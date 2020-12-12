<?php
/**
 * Created by PhpStorm.
 * User: Pascal.BENZONANA
 * Date: 08.05.2017
 * Time: 08:54
 * Update : 11-NOV-2020 - michael.gogniat
 * Simplify index. Remove all pages references.
 */

session_start();

require "../path.php";

require VIEW . "helpers.php";
require "../globalhelpers.php";

require CONTROLLER . "adminController.php";
require CONTROLLER . "drugsController.php";
require CONTROLLER . "mainController.php";
require CONTROLLER . "shiftController.php";
require CONTROLLER . "todoController.php";


require MODEL . ".const.php";
require MODEL . "db_crud.php";

require MODEL . "Base.php";
require MODEL . "User.php";
require MODEL . "Drugs.php";
require MODEL . "Nova.php";
require MODEL . "Shift.php";
require MODEL . "Todo.php";

// nothing's allowed without authentication
if (!isset($_SESSION['user'])) {
    login();
} else {
    // define which action must be performed
    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'home';
    }

    if (function_exists($action)) { // action parameter matches a known function name
        if (isset($_GET['id'])) {
            $action($_GET['id']); // call it with a value
        } else {
            $action();             // or not
        }
    } else {
        home();
    }
}

