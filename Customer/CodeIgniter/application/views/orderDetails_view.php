<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $orders = array();
    foreach($result as $row){
        if(array_key_exists($row->OrderID, $orders)){
            $orders[$row->OrderID]["OrderItems"][] =  array(
                "OrderQuantity" =>$row->OrderQuantity,
                "MobileName" =>$row->MobileName,
                "SaleID"=>$row->SaleID,
                "MainIcon"=>$row->MainIcon,
                "MobilePrice"=>$row->MobilePrice
            );
        }
        else{
            $orders[$row->OrderID] = array(
                "OrderTotal"=>$row->OrderTotal,
                "OrderDate"=>$row->OrderDate
            );
            $orders[$row->OrderID]["OrderItems"][] =  array(
                "OrderQuantity" =>$row->OrderQuantity,
                "MobileName" =>$row->MobileName,
                "SaleID"=>$row->SaleID,
                "MainIcon"=>$row->MainIcon,
                "MobilePrice"=>$row->MobilePrice
            );
        }
    }
    echo json_encode($orders);
?>