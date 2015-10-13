<?php
    require 'verifyEmployee.php';
	$Name = filter_input(INPUT_POST,MobileName,FILTER_SANITIZE_STRING);
	$Description = filter_input(INPUT_POST,MobileDescription,FILTER_SANITIZE_STRING);
	$Quantity = filter_input(INPUT_POST,Quantity,FILTER_SANITIZE_STRING);
	$Price = filter_input(INPUT_POST,MobilePrice,FILTER_SANITIZE_STRING);
	$SystemID = filter_input(INPUT_POST,MobileOS,FILTER_SANITIZE_STRING);
	$BrandID = filter_input(INPUT_POST,MobileBrand,FILTER_SANITIZE_STRING);
	$ScreenSize = filter_input(INPUT_POST,MobileSize,FILTER_SANITIZE_STRING);
	$FrontCamera = filter_input(INPUT_POST,FrontCamera,FILTER_SANITIZE_STRING);
	$RearCamera = filter_input(INPUT_POST,RearCamera,FILTER_SANITIZE_STRING);
	$RAM = filter_input(INPUT_POST,RAM,FILTER_SANITIZE_STRING);
	$InternalMemory = filter_input(INPUT_POST,InternalMemory,FILTER_SANITIZE_STRING);
	$ExtendableMemory = filter_input(INPUT_POST,ExternalMemory,FILTER_SANITIZE_STRING);
	$Bluetooth = $_POST["Bluetooth"];
	$GPS = $_POST["GPS"];
	$Infrared = $_POST["Infrared"];
	$OtherFeatures = filter_input(INPUT_POST,OtherFeatures,FILTER_SANITIZE_STRING);
    $operation = $_POST["operation"];

    $filename="";
    if(strlen($_FILES["MobileMainIcon"]["name"])!=0){
        date_default_timezone_set('America/Los_Angeles');
        $date = new DateTime();
        $timestamp =  $date->getTimestamp();
        $name = $_FILES["MobileMainIcon"]["name"];
        $extension = end(explode(".", $name));
        $filename = $timestamp.".".$extension;
        $target = '/home/scf-36/szamwar/apache2/htdocs/assignment3/images/'.$filename;
    }
    if($operation == "Add") {
        if(move_uploaded_file($_FILES['MobileMainIcon']['tmp_name'], $target)){
            echo "The file has been uploaded, and your information has been added to the directory";
            $sql = "insert into Mobiles (MobileName,Description,Quantity,Price,SystemID,BrandID,ScreenSize,FrontCamera,RearCamera,RAM,InternalMemory,ExtendableMemory,Bluetooth,GPS,Infrared,OtherFeatures,MainIcon) values('$Name','$Description','$Quantity','$Price','$SystemID','$BrandID','$ScreenSize','$FrontCamera','$RearCamera','$RAM','$InternalMemory','$ExtendableMemory','$Bluetooth','$GPS','$Infrared','$OtherFeatures','$filename')";
        }
        else {
            echo $target;
            echo $_FILES['MobileMainIcon'];
        }
    }
    else if($operation == "Update"){
        $id = $_POST['updateMobileID'];
        if(strlen($filename)==0){
            $sql = "update Mobiles set MobileName= '$Name',Description='$Description',Quantity='$Quantity',Price='$Price',SystemID='$SystemID',BrandID='$BrandID',ScreenSize='$ScreenSize',FrontCamera='$FrontCamera',RearCamera='$RearCamera',RAM='$RAM',InternalMemory='$InternalMemory',ExtendableMemory='$ExtendableMemory',Bluetooth='$Bluetooth',GPS='$GPS',Infrared='$Infrared',OtherFeatures='$OtherFeatures' where MobileID='".$id."'";
        }
        else{
            if(move_uploaded_file($_FILES['MobileMainIcon']['tmp_name'], $target)) {
                echo "The file has been uploaded, and your information has been added to the directory";
                $sql = "update Mobiles set MobileName= '$Name',Description='$Description',Quantity='$Quantity',Price='$Price',SystemID='$SystemID',BrandID='$BrandID',ScreenSize='$ScreenSize',FrontCamera='$FrontCamera',RearCamera='$RearCamera',RAM='$RAM',InternalMemory='$InternalMemory',ExtendableMemory='$ExtendableMemory',Bluetooth='$Bluetooth',GPS='$GPS',Infrared='$Infrared',MainIcon='$filename',OtherFeatures='$OtherFeatures' where MobileID='" . $id . "'";
            }
        }

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