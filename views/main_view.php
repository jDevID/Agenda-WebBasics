<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Agendapp - Main view</title>
        <link rel="icon" href="../fav.ico" type="image/x-icon">
        <script src="../public/js/list_rendezvous.js"></script>
        <script src="../public/js/list_client.js"></script>
        <script src="../public/js/toast.js"></script>
        <script src="../public/js/formValidation.js" defer></script>
        <link rel="stylesheet" href="../public/css/toast.css">
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
        if (!isset($_SESSION['username']) || !isset($_SESSION['user_id'])) {
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

        <h1>Edition Rendez-vous</h1>

        <!-- FORM Rdv -->
        <form method="POST" action="../controllers/add_rendezvous.php">
            <label>Nom :
                <select name="user_id">
                    <?php foreach ($users as $user): ?>
                        <?php if ($user->getId() !== $_SESSION['user_id']): ?>
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

        <h1>Edition Client</h1>

        <!-- FORM Client -->
        <form method="POST" action="../controllers/add_client.php">
            <label for="username">Nom:</label><br>
            <input type="text" id="username" name="username" required><br>

            <label for="password">Mot de Passe:</label><br>
            <input type="password" id="password" name="password" required><br>

            <label for="role">Rôle:</label><br>
            <select id="role" name="role" required>
                <option value="admin">Admin</option>
                <option value="client">Client</option>
            </select><br>

            <input type="submit" value="Create Client">
        </form>

        <!-- LISTE Rdv -->
        <h2>Liste Rendez-vous</h2>
        <table id="table_rendezvous_list"></table>

        <!-- LISTE User -->
        <h2>Liste Client</h2>
        <table id="table_client_list"></table>

        <?php
        /*  *  *   *   DÉCONNEXION     *    *   *   */
        echo '<p>' . htmlspecialchars($_SESSION['username']) . ' - <a href="../controllers/logout.php">Se déconnecter</a></p>';
        ?>

        <?php
        /*  *  TOAST  *   */
        getToast();
        ?>

    </body>
</html>
