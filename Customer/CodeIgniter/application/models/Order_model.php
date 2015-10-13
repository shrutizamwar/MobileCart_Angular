<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    public function addOrder()
    {
        $orderTotal = 0;
        $orderLogArray = array();
        $orderItems =array();

        date_default_timezone_set('America/Los_Angeles');
        $today = date('Y-m-d');
        $customerID = $_SESSION['userid'];
        $active = 'True';
        $saleSql = "select * from Mobiles m,Cart cart,Sales s where m.MobileID = cart.MobileID and s.SalesID = cart.SaleID and cart.CustomerID=? and cart.Active=? and ? between s.StartDate and s.EndDate";
        $regularSql = "select *from Mobiles m ,Cart cart where m.MobileID = cart.MobileID and cart.CustomerID=? and cart.Active = ? and cart.SaleID is NULL";

        $saleQueryRes = $this->db->query($saleSql,array($customerID,"$active","$today"))->result();

        foreach($saleQueryRes as $row){
            $orderTotal += (((100 - $row->PercentageOff) / 100) * $row->Price) * $row->Quantity;
            $orderItem =  array(
                "SaleFlag"=>0,
                "MobileID" => $row->MobileID,
                "SaleID"=>$row->SaleID,
                "Quantity"=>$row->Quantity,
                "Price"=> ((100 - $row->PercentageOff) / 100) * $row->Price
            );
            $orderItems[]=$orderItem;
            array_push($orderLogArray,$row->MobileID);
        }

        $regularQueryRes = $this->db->query($regularSql,array($customerID,"$active"))->result();
        foreach($regularQueryRes as $row){
            $orderTotal += $row->Quantity * $row->Price;
            $orderItem = array(
                "SaleFlag"=>1,
                "MobileID" => $row->MobileID,
                "SaleID"=>$row->SaleID,
                "Quantity"=>$row->Quantity,
                "Price"=> $row->Price
            );
            $orderItems[]=$orderItem;
            array_push($orderLogArray,$row->MobileID);
        }

        $data=array(
            "CustomerID" => $customerID,
            "OrderTotal"=>$orderTotal,
            "OrderDate"=>$today
        );

        $this->db->trans_start();
        $this->db->insert('Orders',$data);
        $orderID = $this->db->insert_id();
        $this->addOrderItems($orderItems,$orderID);
        $this->db->trans_complete();

        $this->updateOrderLog($orderLogArray);

    }

    public function addOrderItems($orderItems,$orderID){
        foreach ($orderItems as $orderItem) {

            $data = array(
                "OrderID" => $orderID,
                "MobileID" => $orderItem['MobileID'],
                "SaleFlag" => $orderItem['SaleFlag'],
                "OrderQuantity" => $orderItem['Quantity'],
                "MobilePrice" => $orderItem['Price']
            );

            if($orderItem['SaleID']!= null){
                $data["SaleID"] = $orderItem['SaleID'];
            }
            else{
                $data["SaleID"] = NULL;
            }
            $this->db->insert('OrderItems',$data);
        }
    }

    public function updateOrderLog($orderLogArray){
        for ($i=0; $i<count($orderLogArray); $i++ ){
            for($j=$i+1; $j<count($orderLogArray); $j++){
                $checkSql1 = "select * from ProductSuggestion where MobileID=".$orderLogArray[$i]." and OtherMobileID=".$orderLogArray[$j];
                $res = $this->db->query($checkSql1);
                if($res->num_rows()>0){
                    $newCount= $res->row()->Counter+1;
                    $update = "update ProductSuggestion set Counter='$newCount' where MobileID=".$orderLogArray[$i]." and OtherMobileID=".$orderLogArray[$j];
                    $this->db->query($update);
                }
                else{
                    $insert = "insert into ProductSuggestion(MobileID, OtherMobileID) values($orderLogArray[$i],$orderLogArray[$j])";
                    $this->db->query($insert);
                }

                $checkSql2 = "select * from ProductSuggestion where MobileID=".$orderLogArray[$j]." and OtherMobileID=".$orderLogArray[$i];
                $res = $this->db->query($checkSql2);
                if($res->num_rows() > 0){
                    $newCount= $res->row()->Counter+1;
                    $update = "update ProductSuggestion set Counter='$newCount' where MobileID=".$orderLogArray[$j]." and OtherMobileID=".$orderLogArray[$i];
                    $this->db->query($update);
                }
                else {
                    $insert = "insert into ProductSuggestion(MobileID, OtherMobileID) values($orderLogArray[$j],$orderLogArray[$i])";
                    $this->db->query($insert);
                }
            }
        }
    }

    public function getOrderDetails(){
        $customerID = $_SESSION["userid"];
        $sql = "select * from Orders,OrderItems,Mobiles where Orders.OrderID = OrderItems.OrderID and OrderItems.MobileID=Mobiles.MobileID and Orders.CustomerID=?";
        return $this->db->query($sql,array($customerID))->result();
    }
}