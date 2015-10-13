<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getCartCount(){
        date_default_timezone_set('America/Los_Angeles');
        $today = date('Y-m-d');

        $customerID = $_SESSION['userid'];
        $active = 'True';

        $saleSql = "select * from Cart cart,Sales s where s.SalesID = cart.SaleID and ? between s.StartDate and s.EndDate and cart.CustomerID=? and cart.Active= ?";
        $regularSql = "select *from Cart where CustomerID=? and Active = ? and SaleID is NULL";

        $cartSaleQuery = $this->db->query($saleSql,array("$today",$customerID,$active))->result();
        $cartMobileQuery = $this->db->query($regularSql,array($customerID,$active))->result();
        return array_merge($cartSaleQuery,$cartMobileQuery);
    }

    function addToCart($quantity, $mobileID){
        $customerID = $_SESSION['userid'];
        $active = 'True';
        $saleID = NULL;
        $checkEntrySql = "select * from Cart where CustomerID = ? and MobileID =? and Active=? and SaleID is ?";
        $checkEntryRes = $this->db->query($checkEntrySql,array($customerID,$mobileID,$active,$saleID));
        if($checkEntryRes->num_rows()>0){
            $newQuantity = $checkEntryRes->row()->Quantity+$quantity;
            $this->db->where(array('CustomerID' => $customerID, 'MobileID' => $mobileID, 'Active' => $active,'SaleID' => $saleID));
            $data = array('Quantity'=> $newQuantity);
            $this->db->update('Cart', $data);
        }
        else {
            $data = array(
                "MobileID" => $mobileID,
                "CustomerID" => $customerID,
                "Quantity" => $quantity
            );
            $this->db->insert('Cart', $data);
        }
    }

    function addSaleToCart($quantity,$mobileID,$saleID){
        $customerID = $_SESSION['userid'];
        $active = 'True';
        $checkEntrySql = "select * from Cart where CustomerID = ? and MobileID =? and Active=? and SaleID = ?";
        $checkEntryRes = $this->db->query($checkEntrySql,array($customerID,$mobileID,$active,$saleID));
        if($checkEntryRes->num_rows()>0) {
            $newQuantity = $checkEntryRes->row()->Quantity+$quantity;
            $this->db->where(array('CustomerID' => $customerID, 'MobileID' => $mobileID, 'Active' => $active,'SaleID' => $saleID));
            $data = array('Quantity'=> $newQuantity);
            $this->db->update('Cart', $data);
        }
        else {
            $data = array(
                "MobileID" => $mobileID,
                "CustomerID" => $customerID,
                "Quantity" => $quantity,
                "SaleID"=>$saleID
            );
            $this->db->insert('Cart', $data);
        }
    }

    function getCartItems(){
        date_default_timezone_set('America/Los_Angeles');
        $today = date('Y-m-d');
        $customerID = $_SESSION['userid'];
        $active = 'True';
        $saleSql = "select * from Mobiles m,Cart cart,Sales s where m.MobileID = cart.MobileID and s.SalesID = cart.SaleID  and ? between s.StartDate and s.EndDate and cart.CustomerID=? and cart.Active=?";
        $regularSql = "select *from Mobiles m ,Cart cart where m.MobileID = cart.MobileID and cart.CustomerID=? and cart.Active=? and cart.SaleID is NULL";
        $cartSaleQuery = $this->db->query($saleSql,array("$today",$customerID,$active))->result();
        $cartMobileQuery = $this->db->query($regularSql,array($customerID,$active))->result();
        return array_merge($cartSaleQuery,$cartMobileQuery);
    }

    function updateCartQuantity($cartID,$quantity){
        $customerID = $_SESSION['userid'];
        $this->db->where(array('CustomerID' => $customerID,"CartID"=>$cartID));
        $this->db->update('Cart',array("Quantity" => $quantity));
    }

    function removeItem($cartID){
        $this->db->where(array("CartID" => $cartID));
        $this->db->update('Cart',array("Active"=>"False"));
    }

    function clearCart(){
        $customerID = $_SESSION['userid'];
        $this->db->where("CustomerID" , $customerID);
        $this->db->update('Cart',array("Active"=>"False"));
    }
}
?>