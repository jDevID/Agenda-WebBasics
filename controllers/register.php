<?php

require_once('../models/init.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $user = new User();
    $user->register($username, $password);

    header('Location: login.php');
    exit();

} else {
    require('../views/register_view.php');
}

?>
