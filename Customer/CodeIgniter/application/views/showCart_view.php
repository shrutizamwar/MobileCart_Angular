<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $cartItems = array();
    foreach( $result as $row){
        $cartItem = array(
            "CartID"=> $row->CartID,
            "MobileID"=> $row->MobileID,
            "MainIcon"=>$row->MainIcon,
            "SaleID"=>$row->SaleID,
            "Quantity"=>intval($row->Quantity),
            "MobileName"=>$row->MobileName,
        );

        if($row->SaleID == NULL){
            $cartItem["Price"] =  $row->Price;
            $cartItem["TotalPrice"] = $row->Price*$row->Quantity;
        }
        else{
            $cartItem['PercentageOff'] = $row->PercentageOff;
            $cartItem["Price"]= ((100 - $row->PercentageOff)/100) * $row->Price;
            $cartItem["TotalPrice"] = (((100-$row->PercentageOff)/100) * $row->Price)*$row->Quantity;
        }
        $cartItems[] = $cartItem;
    }
    echo json_encode($cartItems);
?>