<?php
/*  *   *   * CONTROLLER - DEL Client Rdv *   *
 *  envoi les données d'event listeners
 *  utiles à la suppression de tous les
 *  Rdv d'un Client depuis list_client.js
 *  Retourne des informations aux vues en Json
 *  et envoi à la DAL pour exécution
 */
ob_start(); // Buffering on

require_once('../models/init.php');

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

/*  *  *   *   DEL Rdv Client     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        /*  *  *   *   ACTION    *    *   *   */
        $userDAL = new UserDAL();

        /*  *  *   *   PERMISSION    *    *   *   */
        if ($_SESSION['role'] === 'client') {
            echo json_encode(['message' => 'Vous n\'avez pas cette permission.']);
            http_response_code(403);
            exit;

        }
        $rowCount = $userDAL->deleteAllRendezvous($id);

        if ($rowCount > 0) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Tous les Rendez-vous du client ont été supprimés.']);
            http_response_code(200);

        } elseif ($rowCount === 0) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Le client n\'a pas de rendezvous.']);
            http_response_code(200);

        } else {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Erreur lors de la suppression des Rendez-vous du client avec ID ' . $id]);
            http_response_code(500);
        }

    } else {

        /*  *  *   *   RETOUR    *    *   *   */
        echo json_encode(['message' => 'Aucun ID de client fourni.']);
        http_response_code(400);
    }

}

ob_end_flush(); // Buffer close&clear
?>
