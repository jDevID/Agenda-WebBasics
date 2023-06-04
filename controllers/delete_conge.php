<?php
/*  *   *   * CONTROLLER - DEL Congé *   *
 *  Sends form data of congé deletion to Factory
 *  Returns information to views and sends to DAL for execution
 */
ob_start(); // Buffering on

require_once('../models/init.php');
require_once '../models/CongeFactory.php';

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

/*  *  *   *   DEL Congé     *    *   *   */
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
        $congeDAL = new CongeDAL();
        $rendezvousDAL = new RendezvousDAL();
        $congeFactory = new CongeFactory($congeDAL, $rendezvousDAL);

        /*  *  *   *   ACTION    *    *   *   */
        $conge = $congeDAL->getCongeById($id);

        if (!$conge) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Pas de Congé à l'id $id."]);
            http_response_code(404);
            return;
        }

        /*  *  *   *   ACTION    *    *   *   */
        $result = $congeDAL->delete($id);

        if ($result) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => "Congé supprimé."]);
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
