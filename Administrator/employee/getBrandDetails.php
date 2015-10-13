<?php
    require 'verifyEmployee.php';
    $id =  $_POST['id'];
    $sql = "select *from Brands where BrandID ='".$id."'";
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        echo "<h1>Error!!!</h1>";
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);

    if($row = mysql_fetch_assoc($res)){
        mysql_close($conn);
        $details = array(
            "BrandID" => $row['BrandID'],
            "BrandName"=> $row['BrandName'],
            "BrandDescription" => $row['BrandDescription']
        );
        echo json_encode($details);
    }

?>