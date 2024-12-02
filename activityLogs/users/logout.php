<?php
session_start();
require_once '../activity_logs/log_functions.php';

if (isset($_SESSION['userID'])) {
    logActivity($_SESSION['userID'], 'LOGOUT', 'User logged out');
}

session_unset();
session_destroy();
header('Location: login.php');
exit();

?>
