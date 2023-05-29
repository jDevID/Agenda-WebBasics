<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../data/UserDAL.class.php';
require_once '../models/Toast.class.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$toastMessage = Toast::getMessage();
$dal = new UserDAL();

$adminCount = $dal->countUsersByRole('admin');
$clientCount = $dal->countUsersByRole('client');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register</title>
    <link rel="icon" href="../fav.ico" type="image/x-icon">
    <script src="../public/js/toast.js" defer></script>
    <link rel="stylesheet" href="../public/css/toast.css">
</head>
<body>
<h1>Register</h1>
<p>compte(s) admin: <?php echo $adminCount; ?></p>
<p>compte(s) client: <?php echo $clientCount; ?></p>
<form action="../controllers/register.php" method="post">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required minlength="4" maxlength="25">
    <br>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required minlength="6" maxlength="20">
    <br>
    <input type="submit" value="Register">
</form>
<p>Se connecter ? <a href="../controllers/login.php">Login</a></p>

<?php
if (isset($_SESSION['username'])) {
    echo '<p>Déjà connecté: ' . htmlspecialchars($_SESSION['username']) . '. <a href="../views/main_view.php">Rentrer</a> ou <a href="../controllers/logout.php">Se déconnecter</a></p>';
}
?>

<script>
    let toastMessage = '<?php echo addslashes($toastMessage['message']); ?>';
    let toastType = '<?php echo addslashes($toastMessage['type']); ?>';
    if (toastMessage && toastType) {
        window.onload = function() {
            showToast(toastMessage, toastType);
        }
    }
</script>
</body>
</html>
