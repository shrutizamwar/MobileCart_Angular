<?php
    require 'verifyEmployee.php';
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);
    foreach ($_POST['brandIDs'] as $brandID) {
        $sql = "delete from Brands where BrandID = $brandID";
        if(!( mysql_query( $sql, $conn )))
        {
            die('Could not delete data: ' . mysql_error());
        }
    }
    mysql_close($conn);
?>