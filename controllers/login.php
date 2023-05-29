<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../models/init.php');
require_once('../models/UserFactory.php');
require_once '../models/Toast.class.php';

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require('../views/login_view.php');
}
else {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    $result = $user->login($username, $password);

    if ($result) {
        session_start();
        $_SESSION['username'] = $result['username'];
        $_SESSION['user_id'] = $result['id'];
        header('Location: ../views/main_view.php');
        exit();
    } else {
        // si le log-in fails, redirection
        require('../views/login_view.php');
    }
}
