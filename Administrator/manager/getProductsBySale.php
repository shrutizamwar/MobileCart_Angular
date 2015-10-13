<?php
    require 'verifyManager.php';
    $mobileID = "";
    $brandID = "";
    $systemID = "";
    $saleDateFrom = "0000-01-01";
    $saleDateTo = "9999-12-12";
    $salePriceFrom = 0;
    $salePriceTo = 999999;
    if(strlen($_POST['mobileID'])!=0){
        $mobileID = $_POST['mobileID'];
    }
    if(strlen($_POST['mobileBrandID'])!=0){
        $brandID = $_POST['mobileBrandID'];
    }
    if(strlen($_POST['mobileSystemID'])!=0){
        $systemID = $_POST['mobileSystemID'];
    }
    if(strlen($_POST['saleDateFrom'])!=0){
        $saleDateFrom = $_POST['saleDateFrom'];
    }
    if(strlen($_POST['saleDateTo'])!=0){
        $saleDateTo = $_POST['saleDateTo'];
    }
    if(strlen($_POST['salePriceFrom'])!=0){
        $salePriceFrom = $_POST['salePriceFrom'];
    }
    if(strlen($_POST['salePriceTo'])!=0){
        $salePriceTo = $_POST['salePriceTo'];
    }


    $sql = 'select s.SalesID, m.MobileName, m.Price, s.PercentageOff, s.StartDate, s.EndDate from Sales as s,Mobiles as m '
            .'WHERE s.MobileID = m.MobileID AND s.MobileID LIKE "%'.$mobileID.'%" AND m.BrandID LIKE "%'.$brandID.'%" AND m.SystemID LIKE "%'.$systemID.'%"'
            .' AND ((s.StartDate BETWEEN "'.$saleDateFrom .'" AND "'.$saleDateTo.'") OR (s.EndDate BETWEEN "'.$saleDateFrom.'" AND "'.$saleDateTo.'"))';

    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        echo "<h1>Error!!!</h1>";
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $mobiles = array();
    while($row = mysql_fetch_assoc($res)){
        $newPrice = ((100-$row['PercentageOff'])/100) * $row['Price'];
        if($newPrice >= $salePriceFrom and $newPrice <= $salePriceTo ) {
            $mobile = array(
                "SalesID" => $row['SalesID'],
                "MobileName" => $row['MobileName'],
                "Price" => $row['Price'],
                "NewPrice"=>$newPrice,
                "PercentageOff" => $row['PercentageOff'],
                "StartDate" => $row['StartDate'],
                "EndDate" => $row["EndDate"]
            );
            $mobiles[] = $mobile;
        }
    }
    mysql_close($conn);
    echo json_encode($mobiles);
?>