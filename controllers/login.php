<?php
/*  *   *   CONTROLLER - Login  *   *
 *  Responsable de la communication
 *  entre le formulaire login_view
 *  et la Factory, retour d'infos
 *  par la vue
 */
ob_start(); // Buffering on

require_once('../models/init.php');
require_once('../models/UserFactory.php');

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
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require('../views/login_view.php');

} else {

    /*  *  *   *   SANITIZE INPUTS     *    *   *   */
    $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

    /*  *  *   *   DEPENDANCES   *    *   *   */
    $userDAL = new UserDAL();
    $userFactory = new UserFactory($userDAL);

    try {

        /*  *  *   *   ACTION    *    *   *   */
        $user = $userFactory->createUserForLogin($username, $password);
        if ($user->getUsername()) {
            $userDAL->checkUserExists($user->getUsername());
        }
        $user = $userDAL->login($user);

        if ($user) {

            /*  *  *   *   SESSION SETTINGS    *    *   *   */
            $_SESSION['username'] = $user->getUsername();
            $_SESSION['user_id'] = $user->getId();
            $_SESSION['role'] = $user->getRole();
            header('Location: ../views/main_view.php');
            exit();

        } else {

            /*  *  *   *   RETOUR    *    *   *   */
            throw new Exception('Login failed. Vérifiez vos identifiants.');
        }

    } catch (Exception $e) {

        /*  *  *   *   RETOUR    *    *   *   */
        Toast::throwMessage($e->getMessage(), 'error');
        header('Location: ../views/login_view.php');
        exit();
    }

}

ob_end_flush(); // Buffer close&clear
?>
