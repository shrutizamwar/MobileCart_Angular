<?php
    require 'verifyManager.php';
    $operation = $_POST['operation'];
    $searchTerm ="";
    $mobileName="";
    $brandID = "";
    $systemID ="";
    $from = 0;
    $to = 999999;
    if(strlen($_POST['mobileSearchTerm'])!=0){
        $searchTerm = filter_input(INPUT_POST, mobileSearchTerm,FILTER_SANITIZE_STRING);
    }

    if($operation == 'byName'){
        $sql = 'select * from Mobiles where MobileName LIKE "%'.$searchTerm.'%"';
    }
    else if($operation == 'byBrandName'){
        $sql = 'select * from Mobiles where BrandID LIKE "%'.$searchTerm.'%"';
    }
    else if($operation == 'byOS'){
        $sql = 'select * from Mobiles where SystemID LIKE "%'.$searchTerm.'%"';
    }
    else if($operation == 'byPrice'){
        if(strlen($_POST['mobilePriceFrom'])!=0){
            $from = filter_input(INPUT_POST, mobilePriceFrom,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['mobilePriceTo'])!=0){
            $to = filter_input(INPUT_POST, mobilePriceTo,FILTER_SANITIZE_STRING);
        }

        $sql = 'select * from Mobiles where Price BETWEEN '.$from.' AND '.$to;
    }
    else if($operation =='byAll'){
        if(strlen($_POST['mobileName'])!=0){
            $mobileName = filter_input(INPUT_POST,mobileName,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['mobileBrand'])!=0){
            $brandID = filter_input(INPUT_POST,mobileBrand,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['mobileSystem'])!=0){
            $systemID = filter_input(INPUT_POST,mobileSystem,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['mobilePriceFrom'])!=0){
            $from = filter_input(INPUT_POST, mobilePriceFrom,FILTER_SANITIZE_STRING);
        }
        if(strlen($_POST['mobilePriceTo'])!=0){
            $to = filter_input(INPUT_POST, mobilePriceTo,FILTER_SANITIZE_STRING);
        }
        $sql = 'select * from Mobiles where MobileName LIKE "%'.$mobileName.'%" AND BrandID LIKE "%'.$brandID.'%" AND SystemID LIKE "%'.$systemID.'%" AND Price BETWEEN ' .$from. ' AND '. $to;
    }
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        echo "<h1>Error!!!</h1>";
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    $res = mysql_query($sql);
    $mobiles = array();
    while($row = mysql_fetch_assoc($res)){
        $mobile = array(
            "MobileName" => $row['MobileName'],
            "MobilePrice"=> $row['Price'],
            "MobileQuantity" => $row['Quantity'],
            "MobileScreenSize"=> $row['ScreenSize'],
            "MobileInternalMemory"=> $row['InternalMemory'],
            "MobileRAM"=>$row["RAM"],
            "MobileIcon" => $row['MainIcon']
        );
        $mobiles[] = $mobile;
    }
    mysql_close($conn);
    echo json_encode($mobiles);
?>