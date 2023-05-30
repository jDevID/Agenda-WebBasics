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
            echo json_encode(['message' => "Pas de Rendez-vous à l'id $id."]);
            http_response_code(404);
            return;
        }

        $rendezvousDateTime = new DateTime($rendezvous->getDate() . ' ' . $rendezvous->getStartHour(), new DateTimeZone('Europe/Paris'));
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($rendezvousDateTime->getTimestamp() - $now->getTimestamp() < 24 * 60 * 60) {
            echo json_encode(['message' => "Impossible de supprimer un Rendez-vous moins de 24h avant."]);
            http_response_code(403);
            return;
        }

        $result = $rendezvousDAL->delete($id);

        if ($result) {
            echo json_encode(['message' => "Rendez-vous supprimé."]);
            http_response_code(200);
        } else {
            // Error while deleting
            echo json_encode(['message' => "Erreur de suppression id $id."]);
            http_response_code(500);
        }
    } else {
        // No id provided
        http_response_code(400);
    }
}
?>