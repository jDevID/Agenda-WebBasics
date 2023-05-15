<?php
// Create Read Update Delete CONGE
require_once 'session_check.php';
require_once '../models/Conge.class.php';

// Afficher un rapport d'erreur (testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sauvegarde des paramètres action, cible et destinataire
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$date = $_POST['date'] ?? $_GET['date'] ?? null;
$user_id = $_SESSION['user_id'];

$conge = new Conge();

function response($status, $message = '', $data = null)
{
    $logData = ['status' => $status, 'message' => $message, 'data' => $data];
    $logFile = fopen("../debug/crudConge.log", "a");
    if ($logFile === false) {
        die("Error -> Permission read&write requises sur le contenu du dossier /debug");
    }
    fwrite($logFile, json_encode($logData) . "\n");
    fclose($logFile);

    // This will output the JSON response to the caller (AJAX).
    header('Content-Type: application/json');
    echo json_encode($logData);
}

try {
    // Selon l'action du paramètre, on détermine le traitement voulu
    switch ($action) {
        case 'check':
            if (!is_null($date)) {
                $isConge = $conge->checkCongeByDate($date);
                if ($isConge) {
                    response('success', 'The date is a conge', ['isConge' => true]);
                } else {
                    response('success', 'The date is not a conge', ['isConge' => false]);
                }
            } else {
                response('error', 'No date provided for check');
            }
            break;
        case 'get_dates':
            // Fetch all dates here
            $dates = $conge->getAllDates();
            if ($dates) {
                response('success', "conge_crud.php/get_dates -> Conge dates received", $dates);
            } else {
                response('error', "Error -> no dates found");
            }
            break;

        case 'save':
            $result = $conge->creerConge($date);
            if ($result) {
                response('success', 'Conge saved successfully');
            } else {
                response('error', 'Error saving conge');
            }
            break;

        case 'delete':
            if (!is_null($date)) {
                $result = $conge->supprimerCongeByDate($date);
                if ($result) {
                    response('success', 'Conge deleted successfully');
                } else {
                    response('error', 'Error deleting conge');
                }
            } else {
                response('error', 'No date provided for delete');
            }
            break;

        default:
            response('error', 'switch case crud Conge: Action invalide');
            break;
    }
} catch (Exception $e) {
    response('error', 'An error occurred: ' . $e->getMessage());
}
?>