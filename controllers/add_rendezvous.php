<?php
/*  *   *   * CONTROLLER - ADD RDV  *   *
 *  envoi les données de formulaires
 *  de création de Rdv vers Factory
 *  Retourne des informations aux vues
 *  et envoi à la DAL pour exécution
 */
ob_start(); // Buffering on

require_once '../models/init.php';
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
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}

/*  *  *   *   POST Rdv     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_POST['user_id'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $start_hour = $_POST['start_hour'];
    $end_hour = $_POST['end_hour'];

    try {

        /*  *  *   *   DateFormat App Vs Database    *    *   *   */
        $dateObj = DateTime::createFromFormat('d-m-Y', $date);
        if ($dateObj === false) {

            /*  *  *   *   RETOUR    *    *   *   */
            Toast::throwMessage('Format de la date invalide. Utilisez le format: DD-MM-YYYY.');
            header('Location: ../views/main_view.php');
            exit;
        }
        $date = $dateObj->format('Y-m-d');

    } catch (Exception $e) {

        /*  *  *   *   RETOUR    *    *   *   */
        Toast::throwMessage('Exception: ' . $e->getMessage());
        header('Location: ../views/main_view.php');
        exit;
    }

    /*  *  *   *   DEPENDANCES    *    *   *   */
    $rendezvousDAL = new RendezvousDAL();
    $rendezvousFactory = new RendezvousFactory($rendezvousDAL);

    /*  *  *   *   HANDLING    *    *   *   */
    try {
        $rendezvous = $rendezvousFactory->createRendezvous($rendezvousDAL, -1, $description, $date, $start_hour, $end_hour, $user_id, 'Europe/Paris');

        if ($rendezvousDAL->insert($rendezvous)) {

            /*  *  *   *   RETOUR    *    *   *   */
            Toast::throwMessage('Création du Rendez-vous.');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {

        /*  *  *   *   RETOUR    *    *   *   */
        Toast::throwMessage('Exception: ' . $e->getMessage());
        header('Location: ../views/main_view.php');
        exit;
    }

}

ob_end_flush(); // Buffer close&clear
?>
