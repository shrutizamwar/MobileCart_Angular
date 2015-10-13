<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Order extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Utilities');
        $this->utilities->check_session_active();
        $this->load->database();
        $this->load->model('order_model', 'order');
    }

    public function addOrder(){
        $this->order->addOrder();
    }

    public function getOrders(){
        $result = $this->order->getOrderDetails();
        $data = array("result"=>$result);
        $this->load->view('orderDetails_view',$data);
    }
}