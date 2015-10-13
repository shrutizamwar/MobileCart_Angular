<?php
    require 'verifyAdmin.php';
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);

    $userIDs = $_POST['user'];

    if($userIDs) {
        foreach ($userIDs as $userID) {
            $sql = "delete from Users where UserID = $userID";
            if (!(mysql_query($sql, $conn))) {
                die('Could not delete data: ' . mysql_error());
            }
        }
        mysql_close($conn);
        echo("<SCRIPT LANGUAGE='JavaScript'>
                window.alert('Users deleted successfully')
                window.location.href='http://cs-server.usc.edu:2390/assignment2/admin/admin.php';
                </SCRIPT>");
    }
    header("Location: admin.php");
?>