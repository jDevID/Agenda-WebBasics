<?php
/*  *   *   CONTROLLER - Logout  *   *
 *  Permet de se déconnecter de l'app
 *  et de supprimer ses données session
 *  Cookie d'identification compris.
 */

/*  *  *   *   SESSION     *    *   *   */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


/*  *  *   *   SESSION END    *    *   *   */
$_SESSION = array();
session_destroy();

/*  *  *   *   REDIRECTION    *    *   *   */
header('Location:../views/login_view.php');
exit;

?>
