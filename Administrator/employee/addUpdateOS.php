<?php

    require 'verifyEmployee.php';
    $operation = filter_input(INPUT_POST,operation,FILTER_SANITIZE_STRING);
    $OSName = filter_input(INPUT_POST,OSName,FILTER_SANITIZE_STRING) ;
    $OSDescription = $_POST['OSDescription'];
    if($operation == "Add") {
        $sql = "insert into OperatingSystems (SystemName,SystemDescription) values('$OSName','$OSDescription')";
    }
    else if($operation == "Update"){
        $id = $_POST['OSid'];
        $sql = "update OperatingSystems set SystemName = '$OSName',SystemDescription= '$OSDescription' where SystemID='".$id."'";
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