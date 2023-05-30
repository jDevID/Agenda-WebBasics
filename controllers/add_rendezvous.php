<?php
ob_start();

require_once('../models/init.php');
require_once '../models/RendezvousFactory.php';
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
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];

    $date = $_POST['date'];
    try {
        $dateObj = DateTime::createFromFormat('d-m-Y', $date);
        if ($dateObj === false) {
            Toast::throwMessage('Format de la date invalide. Utilisez le format: DD-MM-YYYY.');
            header('Location: ../views/main_view.php');
            exit;
        }

        $date = $dateObj->format('Y-m-d');
    } catch
    (Exception $e) {
        Toast::throwMessage('Caught exception: ' . $e->getMessage());
        header('Location: ../views/main_view.php');
        exit;
    }
    $start_hour = $_POST['start_hour'];
    $end_hour = $_POST['end_hour'];

    $rendezvousDAL = new RendezvousDAL();
    $rendezvousFactory = new RendezvousFactory();

    try {

        $rendezvous = $rendezvousFactory->createRendezvous($rendezvousDAL, -1, $description, $date, $start_hour, $end_hour, $user_id, 'Europe/Paris');

        if ($rendezvousDAL->insert($rendezvous)) {
            Toast::throwMessage('Rendezvous created successfully.');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {
        Toast::throwMessage('try catch add_rendezvous -> UserFactory : Caught exception: ' . $e->getMessage());
        header('Location: ../views/main_view.php');
        exit;
    }
}
