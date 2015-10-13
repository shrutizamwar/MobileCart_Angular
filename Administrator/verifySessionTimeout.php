<?php
    session_start();
    $now = time();
    $lastAccess = $_SESSION['access'];
    if(($now - $lastAccess)> 300){
        session_destroy();
        echo("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Session expired')
                window.location.href='http://cs-server.usc.edu:2390/assignment3/login/login.php';
                </SCRIPT>");
        return;
    }
    else{
        $_SESSION['access'] = $lastAccess;
    }
?>