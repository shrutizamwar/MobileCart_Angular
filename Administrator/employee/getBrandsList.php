<?php
    require 'verifyEmployee.php';
    $sql = "select * from Brands";
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $brands = array();
    while($row = mysql_fetch_assoc($res)){
        $brand = array(
            "BrandName" => $row['BrandName'],
            "BrandDescription"=> $row['BrandDescription'],
            "BrandID"=> $row['BrandID']
        );
        $brands[] = $brand;
    }
    mysql_close($conn);
    echo json_encode($brands);
?>