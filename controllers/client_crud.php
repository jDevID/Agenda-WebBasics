<?php
// Create Read Update Delete CLIENT
require_once 'session_check.php';
require_once '../models/Client.class.php';


// Afficher un rapport d'erreur (testing)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Sauvegarde des paramètres action, cible et destinataire
$action = $_GET['action'] ?? ($_POST['action'] ?? '');
$id = $_POST['id'] ?? null;
$user_id = $_SESSION['user_id'];

$client = new Client();

// Fonction utilitaire pour répondre
function response($status, $message = '', $data = null)
{
    $logData = ['status' => $status, 'message' => $message, 'data' => $data];
    // Le serveur doit avoir rw autorisations  sur ../debug/ pour créer des logs
    $logFile = fopen("../debug/crudClient.log", "a");
    if ($logFile === false) {
        die("Error -> Permission read&write requises sur le contenu du dossier /debug");
    }
    fwrite($logFile, json_encode($logData) . "\n");
    fclose($logFile);


    header('Content-Type: application/json');
    echo json_encode($logData);
}

try {
// Selon l'action du paramètre, on détermine le traitement voulu
    switch ($action) {
        case 'getRendezvousForClient':
            $id = $_GET['id'];
            if (!is_null($id)) {
                $data = $client->getRendezvousForClient($id);
                response('success', '', $data);
            } else {
                response('error', 'getRendezvousForClient -> id est null');
            }
            break;

            // lister les clients
        case 'list':
            $data = $client->getAllClientsArray();
            response('success', '', $data);
            break;

            // sauver un client
        case 'save':
            $name = $_POST['name'];
            $email = $_POST['email'];

            $result = $client->creerClient($name, $email);
            if ($result) {
                response('success', 'Client saved successfully');
            } else {
                response('error', 'Error saving client');
            }
            break;
        // modifier un client
        case 'update':
            if (!is_null($id)) {
                $name = $_POST['name'];
                $email = $_POST['email'];

                $result = $client->modifierClientById($id, $name, $email);

                if ($result) {
                    response('success', 'Client updated successfully');
                } else {
                    response('error', 'Error updating client');
                }
            } else {
                response('error', 'No ID provided for update');
            }
            break;
        // supprimer un client si il n'a plus de rendezvous
        case 'delete':
            if (!is_null($id)) {
                $result = $client->supprimerClientById($id);
                if ($result) {
                    response('success', 'Client deleted successfully');
                } else {
                    response('error', 'Error deleting client');
                }
            } else {
                response('error', 'No ID provided for delete');
            }
            break;

        default:
            response('error', 'switch case crud Client: Action invalide');
            break;
    }
} catch (Exception $e) {
    response('error', 'An error occurred: ' . $e->getMessage());
}

?>
