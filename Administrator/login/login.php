<?php
    session_start();
    $username = filter_input(INPUT_POST,UserName,FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST,Password,FILTER_SANITIZE_STRING);

    $errMessage = "";
    $userType ="";

    if(strlen($username) == 0){
        $errMessage = "Invalid Login";
    }

    if(strlen($password) == 0){
        $errMessage = "Invalid Login";
    }

    if(strlen($username) == 0 && strlen($password) == 0){
        $errMessage = "";
    }

    if(strlen($username) > 0 && strlen($password) > 0){
        $sql = "select UserType from Users where Username = '".$username."' and Password = password('". $password."')";
        $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
        if(!$conn){
            die("Could not connect". mysql_error());
        }
        mysql_select_db("website",$conn);
        $res = mysql_query($sql);
        if(!($row = mysql_fetch_assoc($res))){
            $errMessage = "Invalid Login";
        }
        else{
            $userType = $row["UserType"];
            $_SESSION["access"] = time();
            $_SESSION["UserType"] = $userType;
            $_SESSION["Username"] = $username;
        }
        mysql_close($conn);
    }

    if(strlen($errMessage) > 0){
        require 'preLogin.html';
        echo "<p class='errorMessage'>". $errMessage."</p>";
        require 'postLogin.html';
    }
    elseif (!($res)){
        require 'preLogin.html';
        require 'postLogin.html';
    }
    else{
        switch($userType){
            case "Administrator":
                header("Location: ../admin/admin.php");
                break;

            case "Manager":
                header("Location: ../manager/manager.php");
                break;

            case "Employee":
                header("Location: ../employee/employee.php");
                break;
        }

    }
?>