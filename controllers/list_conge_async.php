<?php
/* CONTROLLER - Dyn. List Conge */
ob_start();

require_once('../models/init.php');
require_once('../models/CongeFactory.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username']) || !isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header('Location: ../views/login_view.php');
    exit();
}

$congeDAL = new CongeDAL();
$rendezvousDAL = new RendezvousDAL();
$congeFactory = new CongeFactory($congeDAL, $rendezvousDAL);
$conges = $congeDAL->getAll($congeFactory);


ob_clean();

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>';

echo '<congeList>';
foreach ($conges as $conge) {
    if ($conge !== null) {
        $id = $conge->getId();
        $date = $conge->getDate();

        $dateObj = DateTime::createFromFormat('Y-m-d', $conge->getDate());
        $date = $dateObj->format('d-m-Y');

        echo '<conge>';
        echo "  <id>$id</id>";
        echo "  <date>$date</date>";
        echo '</conge>';
    }
}
echo '</congeList>';

ob_end_flush();
?>