<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Utilities {

    public function check_session_active(){
        session_start();
        if (!isset($_SESSION['userid'])) {
            header("HTTP/1.0 402 You are not logged in");
            echo json_encode(array('message' => 'You are not logged in'));
            die();
        }
        else{
            $now = time();
            $lastAccess = $_SESSION['access'];
            if(($now - $lastAccess)> 3000){
                session_destroy();
                header("HTTP/1.0 401 Session Expired");
                echo json_encode(array('message' => 'Session Expired'));
                die();
            }
            $_SESSION['access'] = $now;
        }
    }

    public function sanitizeInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlentities($data,ENT_QUOTES);
        return $data;
    }

    public function checkRequired($requiredArr){
        foreach($requiredArr as $field) {
            if (empty($field)) {
                return true;
            }
        }
        return false;
    }
}