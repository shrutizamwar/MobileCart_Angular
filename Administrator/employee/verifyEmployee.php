<?php
    require '../checkSessionTimeout.php';
    session_start();
    $userType = $_SESSION["UserType"];
    if($userType != "Employee"){
        header("Location: ../login/login.php");
    }
?>