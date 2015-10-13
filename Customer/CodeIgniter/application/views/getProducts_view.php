<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $mobiles = array();
    $saleMobileIDs= array();
    $saleResult = $result['saleResult'];
    foreach($saleResult as $row){
        $mobile = array(
            "SaleID" => $row->SalesID,
            "MobileID" => $row->MobileID,
            "MobileName" => $row->MobileName,
            "Description" => $row->Description,
            "Price" => ((100 - $row->PercentageOff) / 100) * $row->Price,
            "ScreenSize" => $row->ScreenSize,
            "RearCamera" => $row->RearCamera,
            "RAM" => $row->RAM,
            "MainIcon" => $row->MainIcon
        );
        $mobiles[] = $mobile;
        array_push($saleMobileIDs, $row->MobileID);
    }

    $mobileResult = $result['mobileResult'];
    foreach($mobileResult as $row){
        if (!in_array($row->MobileID, $saleMobileIDs)) {
            $mobile = array(
                "MobileID" => $row->MobileID,
                "MobileName" => $row->MobileName,
                "Description" => $row->Description,
                "Price" => $row->Price,
                "ScreenSize" => $row->ScreenSize,
                "RearCamera" => $row->RearCamera,
                "RAM" => $row->RAM,
                "MainIcon" => $row->MainIcon
            );
            $mobiles[] = $mobile;
        }
    }
    echo json_encode($mobiles);
?>