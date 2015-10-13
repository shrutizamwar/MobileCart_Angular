<?php defined('BASEPATH') OR exit('No direct script access allowed');
    $row = $result->row();
    date_default_timezone_set('America/Los_Angeles');
    $dateFormat = strtotime($row->CreditCardExpiry);
    $CreditCardExpiry = date('Y-m-d',$dateFormat);
    $details = array(
        "CustomerName" => $row->CustomerName,
        "Gender"=> $row->Gender,
        "Username" => $row->Username,
        "Address" => $row->Address,
        "Zipcode" => intval($row->PinCode),
        "EmailAddress" => $row->EmailAddress,
        "ContactNumber" => $row->ContactNumber,
        "CreditCardNumber" => $row->CreditCardNumber,
        "CreditCardName" => $row->CreditCardName,
        "CreditCardExpiry" => $row->CreditCardExpiry
    );
    echo json_encode($details);
?>