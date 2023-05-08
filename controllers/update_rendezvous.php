<?php
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';
require_once '../validation/validator.php';
require_once '../utils/form_data.php';

// Display error report
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



// Get the form data
$fields = ['name', 'description', 'date', 'start_hour', 'end_hour'];
$form_data = extract_form_data($fields);
$user_id = $_SESSION['user_id'];
$id = $_POST['id'] ?? null;

file_put_contents(__DIR__ . '/../debug/debug.txt', print_r($_POST, true));
// Instance of RDV and set attributes
$rendezvous = new Rendezvous();



if (!validate_required_fields($_POST)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Tous les champs sont requis']);
    exit();
}

if (!validate_name_and_description($form_data)) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Le nom doit contenir entre 3 et 35 caractères et la description entre 20 et 300 caractères']);
    exit();
}

if (!validate_date_format($form_data['date'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Le format de la date est invalide. Utilisez le format AAAA-MM-JJ']);
    exit();
}

if (!validate_hour_format($form_data['start_hour']) || !validate_hour_format($form_data['end_hour'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Le format des heures est invalide. Utilisez le format HH:mm']);
    exit();
}

if (!validate_hour_range($form_data['start_hour'], $form_data['end_hour'])) {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'L\'heure de fin doit être après l\'heure de début']);
    exit();
}

// Envoyer la réponse au format Json
header('Content-Type: application/json');

//... (similar to save_rendezvous.php, but call updateRendezvous method instead)
$result = $rendezvous->updateRendezvous($form_data['id'],$form_data['name'], $form_data['description'], $form_data['date'], $form_data['start_hour'], $form_data['end_hour'], $user_id);
if ($result) {
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error updating rendezvous']);
}
