<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $count = 0;
    foreach( $result as $row){
        $count += $row->Quantity;
    }
    echo json_encode(array("count"=>$count));
?>