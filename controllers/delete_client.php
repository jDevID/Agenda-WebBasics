<?php
ob_start();

require_once('../models/init.php');
require_once '../data/UserDAL.class.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        if ($id == $_SESSION['user_id']) {
            echo json_encode(['message' => 'Operation interdite.']);
            http_response_code(403);
            exit();
        }

        $userDAL = new UserDAL();

        $result = $userDAL->delete($id);

        if ($result) {
            echo json_encode(['message' => 'Suppression du Client.']);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Cet utilisateur a encore des Rendez-vous assignés.']);
            http_response_code(500);
        }
    } else {
        echo json_encode(['message' => 'Aucun ID fourni.']);
        http_response_code(400);
    }
}


