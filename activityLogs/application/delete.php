<?php
session_start();
require_once '../core/models.php';

if (!isset($_SESSION['userID'])) {
    header('Location: ../users/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $applicationID = $_GET['id'];
    $result = deleteApplicant($applicationID); 

    if ($result['statusCode'] === 200) {
        $_SESSION['message'] = $result['message']; 
        $_SESSION['status'] = "200";
    } else {
        $_SESSION['message'] = $result['message'];
        $_SESSION['status'] = "400";
    }
    header('Location: create.php');
    exit();
} else {
    $_SESSION['message'] = "Invalid request.";
    $_SESSION['status'] = "400";
    header('Location: create.php');
    exit();
}
?>
