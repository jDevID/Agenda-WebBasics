<?php
/*  *   *   CONTROLLER - Dyn. List User  *   *
 *  appel de la DAL et de tous les User.
 *  Retourne dynamiquement en Ajax
 *  et XML chaque entry au script
 *  list_client.js
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
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}

/*  *  *   *   DEPENDANCES   *    *   *   */
$userDAL = new UserDAL();
$userFactory = new UserFactory($userDAL);

/*  *  *   *   ACTION    *    *   *   */
$clients = $userDAL->getAllUsers();

ob_clean(); // Buffer clear

/*  *  *   *   AJAX ~ XML    *    *   *   */
header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>';

/*  *  *   *   User LIST    *    *   *   */
echo '<clientList>';
foreach ($clients as $client) {

    if ($client !== null && $client->getId() !== $_SESSION['user_id']) {
        $id = $client->getId();
        $name = $client->getUsername();

        echo '<client>';
        echo "  <id>$id</id>";
        echo "  <username>$name</username>";
        echo '</client>';
    }

}
echo '</clientList>';

ob_end_flush(); // Buffer close&clear
?>
