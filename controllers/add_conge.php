<?php
/*  *   *   * CONTROLLER - ADD CONGE  *   *

 */

ob_start(); // Buffering on

require_once '../models/init.php';
require_once '../models/CongeFactory.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/*  *   *   SESSION     *    *   */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

/*  *  *   *   POST Conge     *    *   */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['date'];

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
    /*  *  *   *   DEPENDENCIES    *    */
    $congeDAL = new CongeDAL();
    $rendezvousDAL = new RendezvousDAL();
    $congeFactory = new CongeFactory($congeDAL, $rendezvousDAL);

    /*  *  *   *   HANDLING    *    *   */
    try {
        /*  *  *   *   PERMISSION    *    *   */
        if ($_SESSION['role'] !== 'admin') {
            Toast::throwMessage('Vous n\'avez pas cette permission.', 'error');
            header('Location: ../views/main_view.php');
            exit;
        }

        $conge = $congeFactory->createConge($congeDAL, -1, $date);

        if ($congeDAL->createConge($conge)) {

            /*  *  *   *   RETURN    *    *   */
            Toast::throwMessage('Création du Congé.', 'success');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {

        /*  *  *   *   RETURN    *    *   */
        Toast::throwMessage('Exception: ' . $e->getMessage(), 'error');
        header('Location: ../views/main_view.php');
        exit;
    }
}

ob_end_flush(); // Buffer close&clear
?>

