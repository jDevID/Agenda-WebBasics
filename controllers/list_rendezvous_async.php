<?php
ob_start();

require_once('../models/init.php');
require_once('../models/RendezvousFactory.php');
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

$rendezvousDAL = new RendezvousDAL();
$rendezvousFactory = new RendezvousFactory($rendezvousDAL);
$timezone = 'Europe/Paris';
$rendezvous = $rendezvousDAL->getAll($rendezvousFactory, $timezone);
ob_clean();

header('Content-Type: text/xml');
echo '<?xml version="1.0" encoding="UTF-8" ?>';
echo '<rendezvousList>';
foreach ($rendezvous as $rdv) {
    if ($rdv !== null) {
        $id = $rdv->getId();

        $userId = $rdv->getUserId();
        $clientName = $rendezvousDAL->getUserNameByUserId($userId);
        $description = $rdv->getDescription();
        $date = $rdv->getDate();
        $start_hour = $rdv->getStartHour();
        $end_hour = $rdv->getEndHour();

        $dateObj = DateTime::createFromFormat('Y-m-d', $rdv->getDate());
        $date = $dateObj->format('d-m-Y');

        echo '<rendezvous>';
        echo "  <id>$id</id>";
        echo "  <clientName>$clientName</clientName>";
        echo "  <description>$description</description>";
        echo "  <date>$date</date>";
        echo "  <start_hour>$start_hour</start_hour>";
        echo "  <end_hour>$end_hour</end_hour>";
        echo '</rendezvous>';
    }
}
echo '</rendezvousList>';
?>
