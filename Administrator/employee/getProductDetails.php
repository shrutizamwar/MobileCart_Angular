<?php
    require 'verifyEmployee.php';
    $id = $_POST['id'];
    $sql = "select *from Mobiles where MobileID ='".$id."'";
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
            "MobileID" => $row['MobileID'],
            "MobileName"=> $row['MobileName'],
            "MobileDescription" => $row['Description'],
            "Quantity" => $row['Quantity'],
            "Price" =>$row['Price'],
            "SystemID" =>$row['SystemID'],
            "BrandID" =>$row['BrandID'],
            "ScreenSize" =>$row['ScreenSize'],
            "FrontCamera" =>$row['FrontCamera'],
            "RearCamera" =>$row['RearCamera'],
            "RAM" =>$row['RAM'],
            "InternalMemory" =>$row['InternalMemory'],
            "ExtendableMemory" =>$row['ExtendableMemory'],
            "Bluetooth" =>$row['Bluetooth'],
            "GPS" =>$row['GPS'],
            "Infrared" =>$row['Infrared'],
            "MainIcon" =>$row['MainIcon'],
            "OtherFeatures"=>$row['OtherFeatures']
        );
        echo json_encode($details);
    }
?>