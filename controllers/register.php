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
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_ADD_SLASHES);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_ADD_SLASHES);


    $userDAL = new UserDAL();
    $userFactory = new UserFactory();

    try {
        // validate the user details
        $user = $userFactory->createUser(-1, $username, $password, 'client');

        if (!$userDAL->isUsernameUnique($username)) {
            Toast::throwMessage('Username is already taken. Please choose another.', 'error');
            header('Location: ../views/register_view.php');
            exit();
        }
        // now register the user
        $result = $userDAL->register($user);

        $registrationSuccessful = $userDAL->register($user);

        if ($registrationSuccessful) {
            Toast::throwMessage('Inscription réussie. Vous pouvez maintenant vous connecter.', 'success');
            header('Location: ../views/login_view.php');
            exit();
        } else {
            Toast::throwMessage('Erreur durant le processus d\'inscription.', 'error');
            header('Location: ../views/register_view.php');
            exit();
        }
    } catch (Exception $e) {
        Toast::throwMessage($e->getMessage(), 'error');
        header('Location: ../views/register_view.php');
        exit();
    }
} else {
    require('../views/register_view.php');
}
?>
