<?php

header('Content-Type: application/json');
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$rendezvous = new Rendezvous();
$data = $rendezvous->getAllRendezvous();
echo json_encode($data);

