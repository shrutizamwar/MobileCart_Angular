<?php
    require 'verifyManager.php';
    $sql = "select * from Mobiles";
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $mobiles = array();
    while($row = mysql_fetch_assoc($res)){
        $mobile = array(
            "MobileName" => $row['MobileName'],
            "MobileID"=> $row['MobileID']
        );
        $mobiles[] = $mobile;
    }
    mysql_close($conn);
    echo json_encode($mobiles);
?>