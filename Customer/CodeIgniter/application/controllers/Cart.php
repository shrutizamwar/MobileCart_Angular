<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cart extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Utilities');
        $this->utilities->check_session_active();
        $this->load->database();
        $this->load->model('cart_model', 'cart');
    }

    public function getCartCount()
    {
        $result = $this->cart->getCartCount();
        $data = array("result" => $result);
        $this->load->view('getCartValue_view', $data);
    }

    public function addToCart()
    {
        $quantity = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'quantity',FILTER_SANITIZE_STRING));
        $mobileID = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'mobileID',FILTER_SANITIZE_STRING));

        if($this->utilities->checkRequired(array($quantity,$mobileID))){
            $valid = false;
        }
        else{
            $valid = $this->validateQuantity($quantity);
        }

        $saleID = false;
        if (strlen($this->utilities->sanitizeInput(filter_input(INPUT_POST,'saleID',FILTER_SANITIZE_STRING))) != 0) {
            $saleID = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'saleID',FILTER_SANITIZE_STRING));
        }

        if($valid){
            if ($saleID) {
                $this->cart->addSaleToCart($quantity, $mobileID, $saleID);
            } else {
                $this->cart->addToCart($quantity, $mobileID);
            }
            $this->getCartCount();
        }
        else{
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }

    }

    public function getCart()
    {
        $result = $this->cart->getCartItems();
        $data = array("result" => $result);
        $this->load->view('showCart_view', $data);
    }

    public function updateCartQuantity($cartID)
    {
        $quantity = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'quantity',FILTER_SANITIZE_STRING));

        if($this->utilities->checkRequired(array($cartID,$quantity))){
            $valid = false;
        }
        else{
            $valid = $this->validateQuantity($quantity);
        }

        if($valid){
            $this->cart->updateCartQuantity($cartID, $quantity);
        }
        else{
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }
    }

    public function removeCartItem($cartID)
    {
        $this->cart->removeItem($cartID);
    }

    public function clearCart()
    {
        $this->cart->clearCart();
    }

    public function validateQuantity($quantity){
        if(!(strval($quantity) == strval(intval($quantity)))){
            return false;
        }

        if(!($quantity>=1 && $quantity<=100)){
            return false;
        }
        return true;
    }
}
?>