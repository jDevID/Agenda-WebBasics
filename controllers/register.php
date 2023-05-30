<?php
/*  *   *   CONTROLLER - Register  *   *
 *  Responsable de la communication
 *  entre le formulaire register_view
 *  et la Factory, retour d'infos
 *  par la vue
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

/*  *  *   *   HANDLING     *    *   *   */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    /*  *  *   *   SANITIZE INPUTS     *    *   *   */
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_ADD_SLASHES);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_ADD_SLASHES);


    /*  *  *   *   DEPENDANCES   *    *   *   */
    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    try {

        /*  *  *   *   ACTION    *    *   *   */
        $user = $userFactory->createUser(-1, $username, $password, 'client');
        $result = $userDAL->register($user);
        $registrationSuccessful = $userDAL->register($user);

        if ($registrationSuccessful) {

            /*  *  *   *   RETOUR    *    *   *   */
            Toast::throwMessage('Inscription réussie. Vous pouvez maintenant vous connecter.', 'success');
            header('Location: ../views/login_view.php');
            exit();

        } else {

            /*  *  *   *   RETOUR    *    *   *   */
            Toast::throwMessage('Erreur durant le processus d\'inscription.', 'error');
            header('Location: ../views/register_view.php');
            exit();

        }

    } catch (Exception $e) {

        /*  *  *   *   RETOUR    *    *   *   */
        Toast::throwMessage($e->getMessage(), 'error');
        header('Location: ../views/register_view.php');
        exit();

    }

} else {

    require('../views/register_view.php');
}

ob_end_flush(); // Buffer close&clear
?>
