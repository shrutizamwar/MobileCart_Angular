<?php
    require '../checkSessionTimeout.php';
    session_start();
    $userType = $_SESSION["UserType"];
    if($userType != "Manager"){
        header("Location: ../login/login.php");
    }
?>