<?php
ob_start();

require_once '../models/init.php';
require_once '../models/CongeFactory.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['conge_id'];
        $date = $_POST['new_date'];

        try {
            /*  *  *   *   Conversion du format de date App Vs Database    *    *   *   */
            $dateObj = DateTime::createFromFormat('d-m-Y', $date);
            if ($dateObj === false) {
                /*  *  *   *   RETOUR    *    *   *   */
                Toast::throwMessage('Formaaaaat de date invalide. Utilisez le format: DD-MM-YYYY.');
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

        $congeDAL = new CongeDAL();
        $rendezvousDAL = new RendezvousDAL();
        $congeFactory = new CongeFactory($congeDAL, $rendezvousDAL);

        $conge = $congeFactory->createConge($congeDAL, $id, $date);

        $congeDAL->update($conge);

        Toast::throwMessage('Congé mis à jour avec succès.', 'success');
    } catch (Exception $e) {
        Toast::throwMessage('Erreur lors de la mise à jour du congé: ' . $e->getMessage(), 'error');
    }

    header('Location: ../views/main_view.php');
    exit();

}

ob_end_flush();
?>
