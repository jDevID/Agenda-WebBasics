<?php
ob_start();

require_once '../models/init.php';
require_once '../models/UserFactory.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['client_id'];
    $username = $_POST['username'];
    $role = $_POST['role'];

    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    try {
        /*  *  *   *   PERMISSION    *    *   *   */
        if ($_SESSION['role'] === 'client') {
            Toast::throwMessage('Vous n\'avez pas cette permission.', 'error');
            header('Location: ../views/main_view.php');
            exit;

        }

        // Fetch the user to get the current password
        $currentUser = $userDAL->getUserByUsername($username);
        $password = $currentUser ? $currentUser->getPassword() : '';

        // Use the current password to create the User object
        $user = $userFactory->createUser($id, $username, $password, $role, false, false);

        if ($userDAL->update($user)) {
            Toast::throwMessage('Mise à jour du client.', 'success');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {
        Toast::throwMessage('Exception: ' . $e->getMessage(), 'error');
        header('Location: ../views/main_view.php');
        exit;
    }
} else {
    Toast::throwMessage('Some required fields are missing.', 'error');
    header('Location: ../views/main_view.php');
    exit;

}

ob_end_flush();
?>
