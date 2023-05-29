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
        print_r($id);
        // Instantiate factory and get timezone
        $factory = new RendezvousFactory();
        $timezone = $_SESSION['timezone'] ?? 'Europe/Paris'; // replace this with the appropriate value if needed

        $rendezvousDAL = new RendezvousDAL();
        $rendezvous = $rendezvousDAL->getRendezvousById($id, $factory, $timezone);

        if (!$rendezvous) {
            Toast::throwMessage("No rendezvous found with ID $id.", "error");
            http_response_code(404);
            return;
        }

        $currentDateTime = new DateTime('now', $rendezvous->getTimezone());
        $rendezvousDateTime = DateTime::createFromFormat('d-m-Y H:i:s', $rendezvous->getDate() . ' ' . $rendezvous->getStartHour(), $rendezvous->getTimezone());

        $interval = $currentDateTime->diff($rendezvousDateTime);
        $hours = $interval->h + ($interval->days * 24);


        if ($hours < 24) {
            Toast::throwMessage("Can't delete a rendezvous if it is less than 24 hours away.", "error");
            http_response_code(403);
        } else {
            $rendezvousDAL->delete($id);
            Toast::throwMessage("Rendezvous deleted successfully.");
            http_response_code(200);
            $result = $rendezvousDAL->delete($id);
            if ($result) {
                Toast::throwMessage("Rendezvous deleted successfully.");
                http_response_code(200);
            } else {
                // Error while deleting
                Toast::throwMessage("Error deleting rendezvous with ID $id.", "error");
                http_response_code(500);
            }
        }
//    }
    }
}
?>
