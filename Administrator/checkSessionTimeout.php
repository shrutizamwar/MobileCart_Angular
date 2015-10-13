<?php
    session_start();
    $now = time();
    $lastAccess = $_SESSION['access'];
    if(($now - $lastAccess)> 300){
        session_destroy();
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            header("HTTP/1.0 401 Session Expired");
            echo json_encode(array('error' => array(
                'message' => 'Session Expired'
            )));
            return;
        }
        else{
            header("Location: ./login/login.php");
        }
    }
    else{
        $_SESSION['access'] = $lastAccess;
    }
?>