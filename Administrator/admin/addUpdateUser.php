<?php
    require 'verifyAdmin.php';
    $username = filter_input(INPUT_POST,Username,FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST,Password,FILTER_SANITIZE_STRING);
    $usertype = $_POST["Usertype"];
    $fname = filter_input(INPUT_POST,Firstname,FILTER_SANITIZE_STRING);
    $lname = filter_input(INPUT_POST,Lastname,FILTER_SANITIZE_STRING);
    $gender = $_POST["Gender"];
    $DOB = filter_input(INPUT_POST, DOB, FILTER_SANITIZE_STRING);
    $Address = filter_input(INPUT_POST, Address, FILTER_SANITIZE_STRING);
    $contactNo = filter_input(INPUT_POST,ContactNumber,FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, Email,FILTER_SANITIZE_STRING);
    $salary = filter_input(INPUT_POST,Salary,FILTER_SANITIZE_STRING);
    if($_POST["submit"] == "Add User") {
        $sql = "insert into Users (Username,Password,UserType,Firstname,Lastname,Gender,DOB,Address,ContactNo,EmailAddress,Salary) values
                ( '$username', password('$password'),'$usertype','$fname','$lname','$gender','$DOB','$Address','$contactNo','$email',$salary)";
    }
    else{
        $uid = $_POST['UserID'];
        $sql = "update Users set Username = '$username', Password = password('$password'), UserType='$usertype', Firstname='$fname',Lastname ='$lname', Gender='$gender',
        DOB='$DOB', Address = '$Address',ContactNo='$contactNo',EmailAddress ='$email' ,Salary='$salary' where UserID = $uid";
    }
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        echo "<h1>Error!!!</h1>";
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    if(!( mysql_query( $sql, $conn )))
    {
        die('Could not enter data: ' . mysql_error());
    }
    mysql_close($conn);
    if($_POST["submit"] == "Add User"){
        echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('User added successfully');
                window.location.href='http://cs-server.usc.edu:2390/assignment2/admin/admin.php';
                </SCRIPT>");
    }
    else{
        echo ("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('User updated successfully');
                window.location.href='http://cs-server.usc.edu:2390/assignment2/admin/admin.php';
                </SCRIPT>");
    }
?>