<?php defined('BASEPATH') OR exit('No direct script access allowed');

    class Product extends CI_Controller{
        public function __construct()
        {
            parent::__construct();
            $this->load->library('Utilities');
            $this->utilities->check_session_active();
            $this->load->database();
            $this->load->model('products_model','products');
        }

        public function getProductsBySearch()
        {
            $brandIDs = $this->input->post('BrandIds');
            $OSIDs = $this->input->post('OSIds');
            $searchTerm = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'searchTerm',FILTER_SANITIZE_STRING));

            $result = $this->products->getProducts($brandIDs,$OSIDs,$searchTerm);
            $data = array("result"=>$result);
            $this->load->view('getProducts_view',$data);
        }

        public function getDetails($mobileID){
            $result = $this->products->getRegularMobileDetails($mobileID);
            $data = array("result"=>$result);
            $this->load->view('productDetails_view',$data);
        }

        public function getSaleMobileDetail( $mobileID, $saleID){
            $result = $this->products->getSaleMobileDetail($mobileID,$saleID);
            $data = array("result"=>$result);
            $this->load->view('saleProductDetails_view',$data);
        }

        public function getProductSuggestion($mobileID){
            $result = $this->products->getProductSuggestion($mobileID);
            $data = array("result" => $result);
            $this->load->view('productSuggestion_view',$data);
        }
    }
?>