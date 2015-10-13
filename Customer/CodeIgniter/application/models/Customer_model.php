<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function addCustomer($customerName,$gender,$username,$password,$email,$contactNo,$address,$zipcode,$creditCardNumber,$creditCardName,$creditCardExpiry){
        $this->db->set('Password','PASSWORD("'.$password.'")',FALSE);
        $customer = array(
            'CustomerName'=> $customerName,
            'Gender'=>$gender,
            'Username'=>$username,
            'Address'=>$address,
            'PinCode'=>$zipcode,
            'EmailAddress'=>$email,
            'ContactNumber'=>$contactNo,
            'CreditCardNumber'=>$creditCardNumber,
            'CreditCardName'=>$creditCardName,
            'CreditCardExpiry'=>$creditCardExpiry,
        );
        $this->db->insert('Customer', $customer);
    }

    function checkUsername($username){
        $this->db->select('*')->from('Customer')->where('Username', $username);
        return $this->db->get();
    }

    function checkUpdateUsername($username){
        $customerID = $_SESSION['userid'];
        $sql = "select * from Customer where Username = ? and CustomerID != ?";
        return $this->db->query($sql,array("$username",$customerID));
    }

    function getDetails(){
        $customerID = $_SESSION['userid'];
        $this->db->select('*')->from('Customer')->where('CustomerID',$customerID);
        return $this->db->get();
    }

    function updateCustomer($customerName,$gender,$username,$email,$contactNo,$address,$zipcode,$creditCardNumber,$creditCardName,$creditCardExpiry){
        $customerID = $_SESSION['userid'];
        $this->db->where('CustomerID',$customerID);
        $customer = array(
            'CustomerName'=> $customerName,
            'Gender'=>$gender,
            'Username'=>$username,
            'Address'=>$address,
            'PinCode'=>$zipcode,
            'EmailAddress'=>$email,
            'ContactNumber'=>$contactNo,
            'CreditCardNumber'=>$creditCardNumber,
            'CreditCardName'=>$creditCardName,
            'CreditCardExpiry'=>$creditCardExpiry,
        );
        $this->db->update('Customer', $customer);
    }

    function checkPassword($password){
        $customerID = $_SESSION['userid'];
        $sql = "select *from Customer where Password = password(?) AND CustomerID =?";
        return $this->db->query($sql,array("$password",$customerID));
    }

    function updatePassword($newPassword){
        $customerID = $_SESSION['userid'];
        $sql="update Customer set Password=password(?) where CustomerID = ?";
        $this->db->query($sql,array("$newPassword",$customerID));
    }
}
?>