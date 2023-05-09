<?php
session_start();
require_once('../models/init.php');

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
        header('Location: ../views/list_appointments_view.php');
        exit();
    } else {
        // si le log-in fails, redirection
        require('../views/login_view.php');
    }
}
