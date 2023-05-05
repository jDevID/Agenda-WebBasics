<?php
session_start();
require_once('../models/init.php');

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}



require('../views/list_appointments_view.php');
