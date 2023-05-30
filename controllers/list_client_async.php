<?php
ob_start();

require_once('../models/init.php');
require_once('../models/UserFactory.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header('Location:login.php');
    exit();
}

$userDAL = new UserDAL();
$userFactory = new UserFactory($userDAL);

$clients = $userDAL->getAllUsers();
ob_clean();

header('Content-Type: application/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
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
?>
