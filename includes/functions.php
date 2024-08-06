<?php
// functions.php
include 'config.php';

$model = new config();

function registerUser($username, $password) {
    global $model;
    return $model->registerUser($username, $password);
}

function loginUser($username, $password) {
    global $model;
    return $model->loginUser($username, $password);
}

function isUserLoggedIn() {
    session_start();
    return isset($_SESSION['user_id']);
}

function getColaboradores($searchTerm = '') {
    global $model;
    return $model->getColaboradores($searchTerm);
}


?>
