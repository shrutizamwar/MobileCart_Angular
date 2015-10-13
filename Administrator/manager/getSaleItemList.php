<?php
    require 'verifyManager.php';
    $sql = "select * from Mobiles,Sales where Sales.MobileID = Mobiles.MobileID";
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
            "MobileID"=> $row['MobileID'],
            "SaleID"=>$row['SalesID'],
            "PercentageOff" =>$row['PercentageOff'],
            "StartDate" =>$row['StartDate'],
            "EndDate"=>$row['EndDate']
        );
        $mobiles[] = $mobile;
    }
    mysql_close($conn);
    echo json_encode($mobiles);
?>