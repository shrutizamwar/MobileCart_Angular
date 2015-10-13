<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Utilities');
        $this->utilities->check_session_active();
        $this->load->database();
        $this->load->model('categories_model','categories');
    }

    public function getSystems(){
        $result = $this->categories->getSystems();
        $data = array("result"=>$result);
        $this->load->view('getAllSystems_view',$data);
    }

    public function getBrands(){
        $result = $this->categories->getBrands();
        $data = array("result"=>$result);
        $this->load->view('getAllBrands_view',$data);
    }
}
?>