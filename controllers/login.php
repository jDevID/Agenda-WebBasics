<?php
// ob_start(); // https://www.php.net/manual/en/function.ob-start.php

require_once('../models/init.php');


if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    require('../views/login_view.php');
}
else {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    // TODO : gérer les méthodes de classe: inscription et log-in
    $result = $user->login($username, $password);

    if ($result) {
        session_start();
        $_SESSION['username'] = $username;
        // header vers la page Vue Générale Calendrier
        header('Location: list_appointments.php');
        exit();
    } else {
        // si le log-in fails, redirection
        require('../views/login_view.php');
    }
}

// ob_end_flush();
