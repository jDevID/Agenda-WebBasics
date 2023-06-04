<?php
ob_start();

require_once '../models/init.php';
require_once '../models/RendezvousFactory.php';

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
        $id = $_POST['rdv_id'];
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


        $rendezvousDAL = new RendezvousDAL();
        $congeDAL = new CongeDAL();
        $rendezvousFactory = new RendezvousFactory($rendezvousDAL, $congeDAL);

        $rendezvous = $rendezvousFactory->createRendezvous(
            $rendezvousDAL,
            $id,
            $description,
            $date,
            $start_hour,
            $end_hour,
            $_SESSION['user_id'],
            'Europe/Paris',
            false
        );

        $rendezvousDAL->update($rendezvous);

        Toast::throwMessage('Rendezvous successfully updated.', 'success');
    } catch (Exception $e) {
        Toast::throwMessage('Error while updating rendezvous: ' . $e->getMessage(), 'error');
    }


    header('Location: ../views/main_view.php');
    exit();

}

ob_end_flush();
?>
