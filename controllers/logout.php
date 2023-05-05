<?php

# https://stackoverflow.com/questions/4303311/what-is-the-difference-between-session-unset-and-session-destroy-in-php

session_start();
session_unset(); #  clear de la variable locale $_SESSION
session_destroy(); # clear des data de la session dans le file system

# Redirection
header('Location:login.php');
exit();

?>
