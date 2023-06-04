<?php
/*  *   *   * CONTROLLER - DEL Rdv *   *
 *  envoi les données de formulaires
 *  de suppression de RDV vers Factory
 *  Retourne des informations aux vues
 *  et envoi à la DAL pour exécution
 */
ob_start(); // Buffering on

require_once('../models/init.php');
require_once '../models/RendezvousFactory.php';

/*  *   *   DEBUG
 * ini_set('display_errors', 1);
 * ini_set('display_startup_errors', 1);
 * error_reporting(E_ALL);
 */

/*  *  *   *   SESSION     *    *   *   */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

/*  *  *   *   DEL Rdv     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        /*  *  *   *   PERMISSION    *    *   *   */
        if ($_SESSION['role'] === 'client'){
            echo json_encode(['message' => 'Vous n\'avez pas cette permission.']);
            http_response_code(403);
            exit;
        }

        /*  *  *   *   DEPENDANCES   *    *   *   */
        $rendezvousDAL = new RendezvousDAL();
        $congeDAL = new CongeDAL();
        $rendezvousFactory = new RendezvousFactory($rendezvousDAL, $congeDAL);

        /*  *  *   *   ACTION    *    *   *   */
        $rendezvous = $rendezvousDAL->getRendezvousById($id);

        if (!$rendezvous) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Pas de Rendez-vous à l'id $id."]);
            http_response_code(404);
            return;
        }

        /*  *  *   *   NO DEL 24Hr AVANT Rdv    *    *   *   */
        $rendezvousDateTime = new DateTime($rendezvous->getDate() . ' ' . $rendezvous->getStartHour(), new DateTimeZone('Europe/Paris'));
        $now = new DateTime('now', new DateTimeZone('Europe/Paris'));

        if ($rendezvousDateTime->getTimestamp() - $now->getTimestamp() < 24 * 60 * 60) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Impossible de supprimer un Rendez-vous moins de 24h avant."]);
            http_response_code(403);
            return;

        }

        /*  *  *   *   ACTION    *    *   *   */
        $result = $rendezvousDAL->delete($id);

        if ($result) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Rendez-vous supprimé."]);
            http_response_code(200);

        } else {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Erreur de suppression id $id."]);
            http_response_code(500);

        }
    } else {
        // No id
        http_response_code(400);
    }

}

ob_end_flush(); // Buffer close&clear
?>
