<?php
/*  *   *   * CONTROLLER - ADD USER  *   *
 *  envoi les données de formulaires
 *  de création d'User vers Factory
 *  Retourne des informations aux vues
 *  et envoi à la DAL pour exécution
 */
ob_start(); // Buffering on

require_once '../models/init.php';
require_once '../models/UserFactory.php';

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

/*  *  *   *   POST User     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    /*  *  *   *   DEPENDANCES    *    *   *   */
    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    /*  *  *   *   HANDLING    *    *   *   */
    try {
        $user = $userFactory->createUser(-1, $username, $password, $role);

        if ($userDAL->register($user)) {

            /*  *  *   *   RETOUR    *    *   *   */
            Toast::throwMessage('Création du Client.', 'success');
            header('Location: ../views/main_view.php');
            exit;
        }
    } catch (Exception $e) {

        /*  *  *   *   RETOUR    *    *   *   */
        Toast::throwMessage('Exception: ' . $e->getMessage(), 'error');
        header('Location: ../views/main_view.php');
        exit;
    }

}

ob_end_flush(); // Buffer close&clear
?>
