<?php
require_once 'session_check.php';

// Display error report
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../models/Rendezvous.class.php';
require_once '../utils/form_data.php';

$id = $_POST['id'] ?? null;


file_put_contents(__DIR__ . '/../debug/debug.txt', print_r($_POST, true));
// Instance of RDV and set attributes
$rendezvous = new Rendezvous();

$result = $rendezvous->deleteRendezvous($id);
if ($result) {
    echo json_encode(['status' => 'delete success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error deleting rendezvous']);
}
