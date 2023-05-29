<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

# All session data -> destroy
$_SESSION = array();
session_destroy();


header('Location:../views/login_view.php');
exit;

?>
