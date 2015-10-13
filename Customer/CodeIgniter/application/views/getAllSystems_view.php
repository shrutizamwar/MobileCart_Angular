<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $systems = array();
    foreach($result as $row){
        $system = array(
            "SystemName" => $row->SystemName,
            "SystemID"=> $row->SystemID
        );
        $systems[] = $system;
    }
    echo json_encode($systems);
?>