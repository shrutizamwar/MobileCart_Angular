<?php
    require 'verifyEmployee.php';
    $operation = $_POST['operation'];
    $mobileID = $_POST['mobileID'] ;
    $percentDiscount = filter_input(INPUT_POST,percentDiscount,FILTER_SANITIZE_STRING);
    $startDate = $_POST['saleStartDate'];
    $endDate = $_POST['saleEndDate'];
    if($operation == "Add") {
        $sql = "insert into Sales (MobileID,StartDate,EndDate,PercentageOff) values('$mobileID','$startDate','$endDate','$percentDiscount')";
    }
    else if($operation == "Update"){
        $id = $_POST['saleID'];
        $sql = "update Sales set MobileID = '$mobileID', PercentageOff = '$percentDiscount',StartDate='$startDate',EndDate='$endDate' where SalesID='".$id."'";
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