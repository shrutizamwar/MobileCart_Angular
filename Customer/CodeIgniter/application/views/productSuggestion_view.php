<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $mobileIDArray = array();
    $mobiles = array();
    $saleResult = $result['saleResult'];
    foreach($saleResult as $row) {
        $mobile = array(
            "SaleID"=>$row->SalesID,
            "MobileID" =>$row->OtherMobileID,
            "MobileName"=> $row->MobileName,
            "Description" => $row->Description,
            "Price"=>((100-$row->PercentageOff)/100) * $row->Price,
            "ScreenSize"=> $row->ScreenSize ,
            "RearCamera"=>$row->RearCamera ,
            "RAM"=>$row->RAM ,
            "Counter"=>$row->Counter,
            "MainIcon"=>$row->MainIcon
        );
        $mobiles[] = $mobile;
        array_push($mobileIDArray,$row->MobileID);
    }

    $mobileResult = $result['mobileResult'];

    foreach($mobileResult as $row) {
        if(!in_array($row->MobileID, $mobileIDArray)) {
            $mobile = array(
                "MobileID" => $row->OtherMobileID,
                "MobileName" => $row->MobileName,
                "Description" => $row->Description,
                "Price" => $row->Price,
                "ScreenSize" => $row->ScreenSize,
                "RearCamera" => $row->RearCamera,
                "RAM" => $row->RAM,
                "Counter"=>$row->Counter,
                "MainIcon" => $row->MainIcon
            );
            $mobiles[] = $mobile;
        }
    }


    function cmp($a, $b)
    {
        if ($a['Counter'] == $b['Counter']) {
            return 0;
        }
        return ($a['Counter'] > $b['Counter']) ? -1 : 1;
    }

    usort($mobiles, "cmp");

    echo json_encode(array_slice($mobiles, 0, 5));

?>