<?php
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';
require_once '../validation/validator.php';
require_once '../utils/form_data.php';

// Display error report
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = $_POST['id'] ?? null;

$user_id = $_SESSION['user_id'];

$rendezvous = new Rendezvous();

function sendResponse($status, $message = '', $data = null) {
    header('Content-Type: application/json');
    echo json_encode(['status' => $status, 'message' => $message, 'data' => $data]);
    exit;
}

switch ($action) {
    case 'list':
        $year = $_GET['year'] ?? date("Y");
        $month = $_GET['month'] ?? date("m");
        $data = $rendezvous->getAllRendezvousArray();
        sendResponse('success', '', $data);
        break;
    case 'save':
        $fields = ['name', 'description', 'date', 'start_hour', 'end_hour'];
        $form_data = extract_form_data($fields);
        $result = $rendezvous->creerRendezvous($form_data['name'], $form_data['description'], $form_data['date'], $form_data['start_hour'], $form_data['end_hour'], $user_id);
        if ($result) {
            sendResponse('success', 'Rendezvous saved successfully');
        } else {
            sendResponse('error', 'Error saving rendezvous');
        }
        break;

    case 'update':
        if (!is_null($id)) {
            $fields = ['name', 'description', 'date', 'start_hour', 'end_hour'];
            $form_data = extract_form_data($fields);
            $result = $rendezvous->modifierRendezvousById($id, $form_data['name'], $form_data['description'], $form_data['date'], $form_data['start_hour'], $form_data['end_hour'], $user_id);
            if ($result) {
                sendResponse('success', 'Rendezvous updated successfully');
            } else {
                sendResponse('error', 'Error updating rendezvous');
            }
        } else {
            sendResponse('error', 'No ID provided for update');
        }
        break;

    case 'delete':
        if (!is_null($id)) {
            $result = $rendezvous->annulerRendezvousById($id);
            if ($result) {
                sendResponse('success', 'Rendezvous deleted successfully');
            } else {
                sendResponse('error', 'Error deleting rendezvous');
            }
        } else {
            sendResponse('error', 'No ID provided for delete');
        }
        break;

    default:
        sendResponse('error', 'switch case crud RDV: Action invalide');
        break;
}
?>
