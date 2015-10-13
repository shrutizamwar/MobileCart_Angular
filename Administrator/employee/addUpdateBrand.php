<?php

    require 'verifyEmployee.php';
    $operation = $_POST['operation'];
    $BrandName = filter_input(INPUT_POST,BrandName,FILTER_SANITIZE_STRING) ;
    $BrandDescription = filter_input(INPUT_POST,BrandDescription,FILTER_SANITIZE_STRING);
    if($operation == "Add") {
        $sql = "insert into Brands (BrandName,BrandDescription) values('$BrandName','$BrandDescription')";
    }
    else if($operation == "Update"){
        $id = $_POST['brandID'];
        $sql = "update Brands set BrandName = '$BrandName',BrandDescription= '$BrandDescription' where BrandID='".$id."'";
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
?>