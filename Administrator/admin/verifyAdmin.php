<?php
    require '../verifySessionTimeout.php';
    session_start();
    $userType = $_SESSION["UserType"];
    if($userType != "Administrator"){
        header("Location: ../login/login.php");
    }
?>