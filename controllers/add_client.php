<?php
ob_start();

require_once('../models/init.php');
require_once '../models/UserFactory.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    try {
        $user = $userFactory->createUser(-1, $username, $password, $role);

        if ($userDAL->register($user)) {
            Toast::throwMessage('Client created successfully.', 'success');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {
        Toast::throwMessage('Caught exception: ' . $e->getMessage(), 'error');
        header('Location: ../views/main_view.php');
        exit;
    }
}
?>