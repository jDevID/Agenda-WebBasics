<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/init.php';
require_once '../models/UserFactory.php';
require_once '../models/Toast.class.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    $user->register($username, $password);

    header('Location: login.php');
    exit();

} else {
    require('../views/register_view.php');
}
?>
