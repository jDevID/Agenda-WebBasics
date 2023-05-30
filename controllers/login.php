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
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    try {
        $user = $userFactory->createUserForLogin($username, $password);

        if ($user->getUsername()) {
            $userDAL->checkUserExists($user->getUsername());
        }

        $user = $userDAL->login($user);

        if ($user) {
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = $user->getRole();
            header('Location: ../views/main_view.php');
            exit();
        } else {
            throw new Exception('Login failed. Vérifiez vos identifiants.');
        }

    } catch (Exception $e) {
        Toast::throwMessage($e->getMessage(), 'error');
        header('Location: ../views/login_view.php');
        exit();
    }
}
?>
