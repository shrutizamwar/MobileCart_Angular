<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $details = array();
    $row = $result->row();
    $details = array(
        "MobileID" =>$row->MobileID,
        "MobileName"=> $row->MobileName,
        "Description" => $row->Description,
        "Price" =>$row->Price,
        "ScreenSize"=> $row->ScreenSize ,
        "FrontCamera"=>$row->FrontCamera ,
        "RearCamera"=>$row->RearCamera ,
        "RAM"=>$row->RAM,
        "InternalMemory"=>$row->InternalMemory ,
        "ExtendableMemory"=>$row->ExtendableMemory,
        "Bluetooth"=>$row->Bluetooth,
        "GPS"=>$row->GPS,
        "Infrared"=>$row->Infrared,
        "MainIcon"=>$row->MainIcon,
        "OtherFeatures"=>$row->OtherFeatures,
    );
    echo json_encode($details);
?>