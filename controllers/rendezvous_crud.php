<?php
// Create Read Update Delete RDV
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';
require_once '../validation/rendezvous_validator.php';

// Afficher un rapport d'erreur (testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Sauvegarde des paramètres action, cible et destinataire
$action = $_GET['action'] ?? $_POST['action'] ?? '';
$id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'];

$rendezvous = new Rendezvous();

// Renvoyer une réponse générique à la situation
function response($status, $message = '', $data = null)
{
    $logData = ['status' => $status, 'message' => $message, 'data' => $data];
    // Le serveur doit avoir RW autorisation  sur ../debug/ pour créer des logs
    $logFile = fopen("../debug/crudRendezvous.log", "a");
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
        // rdv by day
        case 'day':
            $date = $_GET['date'];
            $rendezvousForDay = $rendezvous->getAllRendezvousForDay($date);
            if ($rendezvousForDay) {
                response('success', "rendezvous_crud.php/day -> liste RDV du $date reçue", $rendezvousForDay);
            } else {
                response('error', "Pas de rendez-vous à la date du $date");
            }
            break;

        // Date/count-of-rdv
        case 'get_dates':
            $rendezvousDates = $rendezvous->getCountRendezvousByDate();
            if ($rendezvousDates) {
                response('success', 'rendezvous_crud.php/get_dates -> rdv count by day', $rendezvousDates);
            } else {
                response('error', 'Error -> rendezvousDates n\'est pas un array');
            }
            break;

        case 'list':
            $year = $_GET['annee'] ?? date("Y");
            $month = $_GET['mois'] ?? date("m");
            $data = $rendezvous->getAllRendezvousArray();
            response('success', 'rendezvous_crud.php/list -> liste RDV reçue', $data);
            break;

        case 'save':
            $fields = ['name', 'description', 'date', 'start_hour', 'end_hour', 'client'];
            $form_data = extract_form_data($fields);
            $result = $rendezvous->creerRendezvous($form_data['name'], $form_data['description'], $form_data['date'], $form_data['start_hour'], $form_data['end_hour'], $form_data['client'], $user_id);
            if ($result) {
                response('success', 'crud_rendezvous.php/save -> RDV sauvé');
            } else {
                response('error', 'Error saving rendezvous');
            }
            break;

        case 'update':
            if (!is_null($id)) {
                $fields = ['name', 'description', 'date', 'start_hour', 'end_hour', 'client'];
                $form_data = extract_form_data($fields);
                $result = $rendezvous->modifierRendezvousById($id, $form_data['name'], $form_data['description'], $form_data['date'], $form_data['start_hour'], $form_data['end_hour'], $form_data['client'], $user_id);
                if ($result) {
                    response('success', 'crud_rendezvous.php/update -> RDV updated');
                } else {
                    response('error', 'Error updating rendezvous');
                }
            } else {
                response('error', 'No ID provided for update');
            }
            break;

        case 'delete':
            if (!is_null($id)) {
                $result = $rendezvous->annulerRendezvousById($id);
                if ($result) {
                    response('success', 'crud_rendezvous.php/delete -> RDV deleted');
                } else {
                    response('error', 'Error deleting rendezvous');
                }
            } else {
                response('error', 'No ID provided for delete');
            }
            break;
    }
} catch (Exception $e) {
    response('error', 'rendezvous_crud.php/catch -> switch case Exception' . $e->getMessage());
}
?>
