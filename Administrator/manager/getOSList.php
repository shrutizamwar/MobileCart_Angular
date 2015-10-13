<?php
    require 'verifyManager.php';
    $sql = "select * from OperatingSystems";
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $systems = array();
    while($row = mysql_fetch_assoc($res)){
        $system = array(
            "SystemName" => $row['SystemName'],
            "SystemDescription"=> $row['SystemDescription'],
            "SystemID"=> $row['SystemID']
        );
        $systems[] = $system;
    }
    mysql_close($conn);
    echo json_encode($systems);
?>