<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $details = array();
    $row = $result->row();
    $details = array(
        "MobileID" =>$row->MobileID,
        "SaleID" => $row->SalesID,
        "MobileName"=> $row->MobileName,
        "Description" => $row->Description,
        "PercentageOff" =>$row->PercentageOff,
        "OriginalPrice" =>$row->Price,
        "Price"=>((100-$row->PercentageOff)/100) * $row->Price ,
        "ScreenSize"=> $row->ScreenSize ,
        "FrontCamera"=>$row->FrontCamera ,
        "RearCamera"=>$row->RearCamera ,
        "RAM"=>$row->RAM ,
        "InternalMemory"=>$row->InternalMemory ,
        "ExtendableMemory"=>$row->ExtendableMemory,
        "Bluetooth"=>$row->Bluetooth,
        "GPS"=>$row->GPS,
        "Infrared"=>$row->Infrared,
        "MainIcon"=>$row->MainIcon,
        "OtherFeatures"=>$row->OtherFeatures,
        "StartDate"=>$row->StartDate,
        "EndDate"=>$row->EndDate
    );
    echo json_encode($details);
?>