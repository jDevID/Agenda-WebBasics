<?php
require_once 'session_check.php';
require_once '../models/Rendezvous.class.php';

$rendezvous = new Rendezvous();
$data = $rendezvous->getAllRendezvous();

require('../views/list_appointments_view.php');
