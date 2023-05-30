<?php
ob_start();
ini_set('html_errors', 0);

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

        $userDAL = new UserDAL();
        $rowCount = $userDAL->deleteAllRendezvous($id);

        if ($rowCount > 0) {
            echo json_encode(['message' => 'Tous les Rendez-vous du client ont été supprimés.']);
            http_response_code(200);
        } elseif ($rowCount === 0) {
            echo json_encode(['message' => 'Le client n\'a pas de rendezvous.']);
            http_response_code(200);
        } else {
            echo json_encode(['message' => 'Erreur lors de la suppression des Rendez-vous du client avec ID ' . $id]);
            http_response_code(500);
        }
    } else {
        echo json_encode(['message' => 'Aucun ID de client fourni.']);
        http_response_code(400);
    }
}