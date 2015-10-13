<?php
    $sql = "select s.SalesID, m.MobileName,s.PercentageOff,s.StartDate,s.EndDate from Sales s,Mobiles m where s.MobileID = m.MobileID";
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $sales = array();
    while($row = mysql_fetch_assoc($res)){
        $sale = array(
            "SalesID"=>$row['SalesID'],
            "MobileName" => $row['MobileName'],
            "PercentageOff"=> $row['PercentageOff'],
            "StartDate"=> $row['StartDate'],
            "EndDate"=> $row['EndDate']
        );
        $sales[] = $sale;
    }
    mysql_close($conn);
    echo json_encode($sales);
?>