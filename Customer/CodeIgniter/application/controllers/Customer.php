<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('customer_model','customer');
        $this->load->library('Utilities');
    }

    public function getDetails(){
        $this->utilities->check_session_active();
        $result = $this->customer->getDetails();
        $data = array("result"=>$result);
        $this->load->view('customerDetails_view',$data);
    }

    public function addCustomer(){
        $customerName = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'customerName',FILTER_SANITIZE_STRING));
        $gender = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'gender',FILTER_SANITIZE_STRING));
        $username = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING));
        $password =$this->utilities->sanitizeInput(filter_input(INPUT_POST,'password',FILTER_SANITIZE_STRING));
        $email = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'emailAddress',FILTER_SANITIZE_STRING));
        $contactNo = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'contactNumber',FILTER_SANITIZE_STRING));
        $address = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING));
        $zipcode = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING));
        $creditCardNumber = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardNumber',FILTER_SANITIZE_STRING));
        $creditCardName = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardName',FILTER_SANITIZE_STRING));
        $creditCardExpiry = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardExpiry',FILTER_SANITIZE_STRING).'-01');

        $valid = $this->validateAddCustomer($customerName, $gender, $username, $password, $email, $contactNo, $address, $zipcode, $creditCardNumber, $creditCardName, $creditCardExpiry);
        if($valid){
            $this->customer->addCustomer($customerName, $gender, $username, $password, $email, $contactNo, $address, $zipcode, $creditCardNumber, $creditCardName, $creditCardExpiry);
        }
        else{
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }
    }

    public function updateCustomer(){
        $customerName = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'customerName',FILTER_SANITIZE_STRING));
        $gender = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'gender',FILTER_SANITIZE_STRING));
        $username = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING));
        $email = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'emailAddress',FILTER_SANITIZE_STRING));
        $contactNo = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'contactNumber',FILTER_SANITIZE_STRING));
        $address = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'address',FILTER_SANITIZE_STRING));
        $zipcode = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'zipcode',FILTER_SANITIZE_STRING));
        $creditCardNumber = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardNumber',FILTER_SANITIZE_STRING));
        $creditCardName = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardName',FILTER_SANITIZE_STRING));
        $creditCardExpiry = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'creditCardExpiry',FILTER_SANITIZE_STRING).'-01');

        $valid = $this->validateUpdateCustomer($customerName, $gender, $username, $email, $contactNo, $address, $zipcode, $creditCardNumber, $creditCardName, $creditCardExpiry);
        if($valid){
            $this->utilities->check_session_active();
            $result = $this->customer->checkUpdateUsername($username);
            if($result->num_rows() > 0){
                $data = array("message"=>"Username not available");
                $this->output->set_status_header('500');
                $this->load->view('error_view',$data);
            }
            else{
                $this->customer->updateCustomer($customerName,$gender,$username,$email,$contactNo,$address,$zipcode,$creditCardNumber,$creditCardName,$creditCardExpiry);
            }
        }
        else{
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }

    }

    public function updatePassword(){
        $this->utilities->check_session_active();
        $oldPassword = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'oldPassword',FILTER_SANITIZE_STRING));

        $result = $this->customer->checkPassword($oldPassword);
        if($result->num_rows() > 0) {
            $newPassword = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'newPassword',FILTER_SANITIZE_STRING));

            if($this->utilities->checkRequired(array($newPassword))){
                $data = array("message"=>"Invalid Input");
                $this->output->set_status_header('400');
                $this->load->view('error_view',$data);
            }
            else{
                $this->customer->updatePassword($newPassword);
            }
        }
        else{
            $data = array("message"=>"Old Password is wrong");
            $this->output->set_status_header('500');
            $this->load->view('error_view',$data);
        }
    }

    public function checkUsername(){
        session_start();
        $username = $this->utilities->sanitizeInput(filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING));

        if($this->utilities->checkRequired(array($username))){
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }
        else{
            $result = $this->customer->checkUsername($username);
            if($result->num_rows()>0){
                $data = array("message"=>"Username not available");
                $this->output->set_status_header('500');
                $this->load->view('error_view',$data);
            }
        }
    }

    public function validateAddCustomer($customerName, $gender, $username, $password, $email, $contactNo, $address, $zipcode, $creditCardNumber, $creditCardName, $creditCardExpiry){
        if($this->utilities->checkRequired(array($customerName,$username,$password,$email,$address,$zipcode,$creditCardNumber,$creditCardName,$creditCardExpiry))){
            return false;
        }

        if(strlen($gender) > 0){
            if(!($gender == "Male" || $gender== "Female")) {
                return false;
            }
        }

        if(preg_match('/^([\w\.\%\+\-]+)@([a-z0-9\.\-]+\.[a-z]{2,6})$/i', $email) == 0){
            return false;
        }

        if(strlen($contactNo) > 0){
            if(preg_match('/^\d{3}-\d{3}-\d{4}$/',$contactNo) == 0 || strlen($contactNo)!=12){
                return false;
            }
        }

        if(strlen($zipcode) !=5 ){
            return false;
        }

        if(!($zipcode>=10000 && $zipcode <= 99999)){
            return false;
        }

        if(strlen($creditCardNumber)!=16){
            return false;
        }

        if(!($creditCardNumber>=1000000000000000 && $creditCardNumber<=9999999999999999)){
            return false;
        }
        return true;
    }

    public function validateUpdateCustomer($customerName, $gender, $username, $email, $contactNo, $address, $zipcode, $creditCardNumber, $creditCardName, $creditCardExpiry){
        if($this->utilities->checkRequired(array($customerName,$username,$email,$address,$zipcode,$creditCardNumber,$creditCardName,$creditCardExpiry))){
            return false;
        }

        if(strlen($gender) > 0){
            if(!($gender == "Male" || $gender== "Female")) {
                return false;
            }
        }

        if(preg_match('/^([\w\.\%\+\-]+)@([a-z0-9\.\-]+\.[a-z]{2,6})$/i', $email) == 0){
            return false;
        }

        if(strlen($contactNo) > 0){
            if(preg_match('/^\d{3}-\d{3}-\d{4}$/',$contactNo) == 0 || strlen($contactNo)!=12){
                return false;
            }
        }

        if(strlen($zipcode) !=5 ){
            return false;
        }

        if(!($zipcode>=10000 && $zipcode <= 99999)){
            return false;
        }

        if(strlen($creditCardNumber)!=16){
            return false;
        }

        if(!($creditCardNumber>=1000000000000000 && $creditCardNumber<=9999999999999999)){
            return false;
        }
        return true;
    }
}
?>