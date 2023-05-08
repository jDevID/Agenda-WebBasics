<?php
// start la session quand appelé
session_start();

// redirection si non-identifié
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}
