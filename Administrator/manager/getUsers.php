<?php
    require 'verifyManager.php';
    $operation = $_POST['operation'];
    $searchTerm = "";
    if(strlen($_POST['userSearchTerm'])!=0) {
        $searchTerm = filter_input(INPUT_POST, userSearchTerm, FILTER_SANITIZE_STRING);
    }
    $from = 0;
    $to = 999999;
    $userName="";
    $userType="";
    if($operation == 'byUserType'){
        $sql = 'select * from Users where UserType LIKE "%'.$searchTerm.'%"';
    }
    else if($operation == 'byUserPay'){
        if(strlen($_POST['userPayFrom'])!=0) {
            $from = $_POST['userPayFrom'];
        }
        if(strlen($_POST['userPayTo'])!=0){
            $to = $_POST['userPayTo'];
        }
        $sql = 'select * from Users where Salary BETWEEN '.$from.' AND '.$to;
    }
    else if($operation == 'byUserName'){
        $sql = "select * from Users where Username LIKE '%".$searchTerm."%'";
    }
    else if($operation == 'byAll'){
        if(strlen($_POST['userName'])!=0){
            $userName = filter_input(INPUT_POST, userName,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['userType'])!=0){
            $userType = filter_input(INPUT_POST, userType,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['userPayFrom'])!=0) {
            $from = filter_input(INPUT_POST, userPayFrom, FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['userPayTo'])!=0) {
            $to = filter_input(INPUT_POST, userPayTo, FILTER_SANITIZE_STRING);
        }
        $sql = 'select * from Users where UserType LIKE "%'.$userType.'%" AND Username LIKE "%'.$userName.'%" AND Salary BETWEEN '.$from. ' AND '.$to;
    }
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        echo "<h1>Error!!!</h1>";
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $users = array();
    while($row = mysql_fetch_assoc($res)){
        $user = array(
            "UserType" => $row['UserType'],
            "FirstName"=> $row['Firstname'],
            "LastName" => $row['Lastname'],
            "Gender"=> $row['Gender'],
            "EmailAddress"=> $row['EmailAddress'],
            "Salary"=>$row['Salary']
        );
        $users[] = $user;
    }
    mysql_close($conn);
    echo json_encode($users);
?>