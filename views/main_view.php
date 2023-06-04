<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agendapp - Main view</title>
    <link rel="icon" href="../fav.ico" type="image/x-icon">
    <script src="../public/js/list_rendezvous.js"></script>
    <script src="../public/js/list_client.js"></script>
    <script src="../public/js/list_conge.js"></script>
    <script src="../public/js/toast.js"></script>
    <script src="../public/js/formValidation.js" defer></script>
    <link rel="stylesheet" href="../public/css/toast.css">
    <link rel="stylesheet" href="../public/css/layout.css">
</head>
<body>

<?php
/*  *   *   *  VIEW - MAIN   *   *   *
 * Toast, affichage des listes, load
 *  des scripts js et fichiers php
 *  Base de l'Application.
 */

require_once('../models/init.php');

/*  *   *   DEBUG */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


/*  *  *   *   SESSION     *    *   *   */
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

/*  *  *   *   GET TOAST     *    *   *   */
function getToast()
{
    $toast = Toast::getMessage();
    if (!empty($toast['message'])) {
        echo '<script type="text/javascript">',
        'showToast("', $toast['message'], '", "', $toast['type'], '");',
        '</script>';
    }
}

/*  *  *   ACTION    *   *   */
$userDAL = new UserDAL();
$users = $userDAL->getAllUsers();
?>

<button id="switchButton">Congés</button>

<div id="rowRdv" class="row">
    <div id="edition-rdv" class="subsection">
        <h1>Edition Rendez-vous</h1>
        <!-- FORM Rdv -->
        <form method="POST" action="../controllers/add_rendezvous.php">
            <label>Nom :

                <select name="user_id">
                    <?php foreach ($users as $user): ?>
                        <?php if ($user->getId() !== $_SESSION['user_id'] && ($user->getRole() === 'admin' || $_SESSION['role'] !== 'client')): ?>
                            <option value="<?= $user->getId(); ?>"><?= $user->getUsername(); ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>

            </label>
            <br>
            <label for="description">Description:</label><br>
            <textarea id="description" name="description" minlength="20" maxlength="300" required></textarea><br>

            <label for="date">Date (DD-MM-YYYY):</label><br>
            <input type="text" id="date" name="date" pattern="\d{2}-\d{2}-\d{4}" required><br>

            <label for="start_hour">Début (format: HH:MM):</label><br>
            <input type="text" id="start_hour" name="start_hour" pattern="\d{2}:\d{2}" required><br>

            <label for="end_hour">Fin (format: HH:MM):</label><br>
            <input type="text" id="end_hour" name="end_hour" pattern="\d{2}:\d{2}" required><br>

            <input type="submit" value="Create Rendezvous">
        </form>
    </div>
    <div id="list-rdv" class="subsection">
        <!-- LISTE Rdv -->
        <h2>Liste Rendez-vous</h2>
        <table id="table_rendezvous_list"></table>
    </div>
    <div id="update-rdv" class="subsection">
        <h1>Update Rendez-vous</h1>
        <form id="form" method="POST" action="../controllers/update_rendezvous.php">
            <label>Rendezvous :
                <input type="number" id="rdv_idUpdate" name="rdv_id" required><br>
            </label>
            <br>
            <label for="descriptionUpdate">Description:</label><br>
            <textarea id="descriptionUpdate" name="description" minlength="20" maxlength="300" required></textarea><br>

            <label for="dateUpdate">Date (DD-MM-YYYY):</label><br>
            <input type="text" id="dateUpdate" name="date" pattern="\d{2}-\d{2}-\d{4}" required><br>

            <label for="start_hourUpdate">Début (format: HH:MM):</label><br>
            <input type="text" id="start_hourUpdate" name="start_hour" pattern="\d{2}:\d{2}" required><br>

            <label for="end_hourUpdate">Fin (format: HH:MM):</label><br>
            <input type="text" id="end_hourUpdate" name="end_hour" pattern="\d{2}:\d{2}" required><br>

            <input type="submit" value="Update Rendezvous">
        </form>
    </div>
</div>

<div id="rowConge" class="row" style="display: none;">

    <div id="edition-conge" class="subsection">
        <h1>Edition Congé</h1>
        <!-- FORM Congé -->
        <form method="POST" action="../controllers/add_conge.php">
            <label for="dateConge">Date (DD-MM-YYYY):</label><br>
            <input type="text" id="dateConge" name="date" pattern="\d{2}-\d{2}-\d{4}" required><br>
            <input type="submit" value="Create Congé">
            <br>

        </form>
    </div>
    <div id="list-conge" class="subsection">
        <!-- LISTE Congé -->
        <h2>Liste Congé</h2>
        <table id="table_conge_list"></table>
    </div>
    <div id="update-conge" class="subsection">
        <h1>Update Congé</h1>
        <form method="POST" action="../controllers/update_conge.php">
            <label>Congé id :
                <input type="number" id="conge_id" name="conge_id" required><br>
            </label>
            <br>
            <label for="new_date">New Date (DD-MM-YYYY):</label><br>
            <input type="text" id="new_date" name="new_date" pattern="\d{2}-\d{2}-\d{4}" required><br>
            <input type="submit" value="Update Congé">
        </form>
    </div>
</div>


<div id="rowClient" class="row">
    <div id="edition-client" class="subsection">
        <h1>Edition Client</h1>

        <form method="POST" id="client-form" action="../controllers/add_client.php">
            <input type="hidden" id="id" name="id">
            <label for="username">Nom:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password" id="password-label">Mot de Passe:</label><br>
            <input type="password" id="password" name="password" required><br>

            <label for="role">Rôle:</label><br>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
            </select><br>

            <input type="submit" id="client-form-submit" value="Create Client">
        </form>
    </div>

    <div id="list-client" class="subsection">
        <!-- LISTE User -->
        <h2>Liste Client</h2>
        <table id="table_client_list"></table>
        <?php
        /*  *  *   *   DÉCONNEXION     *    *   *   */
        echo '<p>' . htmlspecialchars($_SESSION['username']) . ' - <a href="../controllers/logout.php">Se déconnecter</a></p>';
        ?>
    </div>
    <div id="update-client" class="subsection">

        <h1>Update Client</h1>


        <form method="POST" action="../controllers/update_client.php">
            <label>Client ID :
                <input type="number" id="client_id" name="client_id" required><br>
            </label>
            <br>
            <label for="usernameUpdate">Name:</label><input type="text" id="usernameUpdate" name="username"><br>


            <label for="roleUpdate">Role:</label><br>
            <select id="roleUpdate" name="role">
                <option value="admin">Admin</option>
                <option value="client">Client</option>
            </select><br>

            <input type="submit" value="Update Client">
        </form>

    </div>

</div>


<?php
/*  *  TOAST  *   */
getToast();
?>

</body>
</html>
