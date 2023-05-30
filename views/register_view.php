<?php
/*  *   *   * VIEW - REGISTER  *   *   *
 *  fait valider puis envoi les données
 *  de formulaires de création d'User vers
 *  la DAL. Affiche les Toast ici
 */

require_once '../data/UserDAL.class.php';
require_once '../models/Toast.class.php';

/*  *   *   DEBUG
 * ini_set('display_errors', 1);
 * ini_set('display_startup_errors', 1);
 * error_reporting(E_ALL);
 */


/*  *  *   *   SESSION     *    *   *   */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


/*  *  *   *   TOAST     *    *   *   */
$toastMessage = Toast::getMessage();
$dal = new UserDAL();

/*  *  *   *   VAR COMPTEURS     *    *   *   */
$adminCount = $dal->countUsersByRole('admin');
$clientCount = $dal->countUsersByRole('client');
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Agendapp - Inscription</title>
        <link rel="icon" href="../fav.ico" type="image/x-icon">
        <script src="../public/js/toast.js" defer></script>
        <script src="../public/js/formValidation.js" defer></script>
        <link rel="stylesheet" href="../public/css/toast.css">
    </head>
    <body>
        <h1>Register</h1>

        <!-- COMPTEURS -->
        <p>compte(s) admin: <?php echo $adminCount; ?></p>
        <p>compte(s) client: <?php echo $clientCount; ?></p>

        <!-- FORM REGISTER -->
        <form action="../controllers/register.php" method="post">
            <label for="username">Nom:</label>
            <input type="text" name="username" id="username" required minlength="4" maxlength="25" pattern="^[\p{L} ]{4,25}$">
            <br>
            <label for="password">Mot de passe:</label>
            <input type="password" name="password" id="password" required minlength="6" maxlength="20"
                   pattern="^(?=.*[A-Z])(?=.*\d)[^<>=\/\\\s]{6,20}$">
            <br>
            <input type="submit" value="Register">
        </form>

        <!-- GO TO LOGIN -->
        <p>Se connecter ? <a href="../controllers/login.php">Login</a></p>

        <?php
            if (isset($_SESSION['username'])) {
                // Entrer ou se Déconnecter
                echo '<p>Déjà connecté: ' . htmlspecialchars($_SESSION['username']) . '. <a href="../views/main_view.php">Rentrer</a> ou <a href="../controllers/logout.php">Se déconnecter</a></p>';
            }
        ?>

        <script>
            // AFFICHAGE TOAST
            let toastMessage = '<?php echo addslashes($toastMessage['message']); ?>';
            let toastType = '<?php echo addslashes($toastMessage['type']); ?>';
            if (toastMessage && toastType) {
                window.onload = function () {
                    showToast(toastMessage, toastType);
                }
            }
        </script>

    </body>
</html>
