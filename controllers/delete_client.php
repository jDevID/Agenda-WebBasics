<?php
/*  *   *   * CONTROLLER - DEL Client  *   *
 *  envoi les données de formulaires
 *  de suppression d'User vers Factory
 *  Retourne des informations aux vues
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
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}

/*  *  *   *   DEL User     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id'])) {
        $id = $_POST['id'];


        /*  *  *   *   NO SELF DEL   *    *   *   */
        if ($id == $_SESSION['user_id']) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Opération interdite.']);
            http_response_code(403);
            exit();
        }

        /*  *  *   *   ACTION    *    *   *   */
        $userDAL = new UserDAL();
        $result = $userDAL->delete($id);

        if ($result) {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Suppression du Client.']);
            http_response_code(200);

        } else {

            /*  *  *   *   RETOUR    *    *   *   */
            echo json_encode(['message' => 'Cet utilisateur a encore des Rendez-vous assignés.']);
            http_response_code(500);
        }

    } else {

        /*  *  *   *   RETOUR    *    *   *   */
        echo json_encode(['message' => 'Aucun ID fourni.']);
        http_response_code(400);
    }

}

ob_end_flush(); // Buffer close&clear
?>
