<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $brands = array();
    foreach($result as $row){
        $brand = array(
            "BrandName" => $row->BrandName,
            "BrandID"=> $row->BrandID
        );
        $brands[] = $brand;
    }
    echo json_encode($brands);
?>