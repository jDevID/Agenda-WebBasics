<?php
require_once('../models/init.php'); // Accès aux modèles par fichier d'initialisat°

session_start();
// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}
// Récupération des éventuels paramètres du GET Request
$year = isset($_GET['year']) ? intval($_GET['year']) : null;
$month = isset($_GET['month']) ? intval($_GET['month']) : null;

if ($year === null || $month === null) {
    // retour d'un tableau vide si params à null
    echo json_encode([]);
    exit();
}

$data = [];
// indiquer que la réponse est au format JSON
header('Content-Type: application/json');
// Conversion du tableau de données en JSON et envoi de la réponse
echo json_encode($data);

// TODO : Gestion des data de rdv+congés