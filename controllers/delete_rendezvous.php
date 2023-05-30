<?php
ob_start();

require_once('../models/init.php');
require_once '../data/RendezvousDAL.class.php';
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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $timezone = $_SESSION['timezone'] ?? 'Europe/Paris';

        $rendezvousDAL = new RendezvousDAL();
        $factory = new RendezvousFactory($rendezvousDAL);

        $rendezvous = $rendezvousDAL->getRendezvousById($id, $factory, $timezone, false);

        if (!$rendezvous) {
            Toast::throwMessage("No rendezvous found with ID $id.", "error");
            http_response_code(404);
            return;
        }

        $result = $rendezvousDAL->delete($id);

        if ($result) {
            Toast::throwMessage("Rendezvous deleted successfully.");
            http_response_code(200);
        } else {
            // Error while deleting
            Toast::throwMessage("Error deleting rendezvous with ID $id.", "error");
            http_response_code(500);
        }
    } else {
        // No id provided
        http_response_code(400);
    }
}
?>