<?php
ob_start();

require_once('../models/init.php');
require_once '../models/UserFactory.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
