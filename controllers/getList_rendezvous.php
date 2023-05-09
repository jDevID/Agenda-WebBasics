<?php

header('Content-Type: application/json');
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$year = isset($_GET['year']) ? (int)$_GET['year'] : date("Y");
$month = isset($_GET['month']) ? (int)$_GET['month'] : date("m");

$rendezvous = new Rendezvous();
$data = $rendezvous->getRendezvousData($year, $month);

// wrap le json payload du rdv
header('Content-Type: application/json');
// dans l'array $data pour vérifier validité
echo json_encode(['status' => 'success', 'data' => $data]);

