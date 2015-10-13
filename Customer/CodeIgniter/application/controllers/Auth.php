<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('Utilities');
        $this->load->model('login_model');
    }

    public function login()
    {
        $username = $this->utilities->sanitizeInput(filter_input(INPUT_POST,"Username",FILTER_SANITIZE_STRING));
        $password = $this->utilities->sanitizeInput(filter_input(INPUT_POST,"Password",FILTER_SANITIZE_STRING));

        if($this->utilities->checkRequired(array($username,$password))){
            $data = array("message"=>"Invalid Input");
            $this->output->set_status_header('400');
            $this->load->view('error_view',$data);
        }
        else{
            $result = $this->login_model->getUser($username, $password);
            if ($result->num_rows()>0) {
                $row = $result->row();
                session_start();
                $_SESSION['loggedIn'] = true;
                $_SESSION['userid'] = $row->CustomerID;
                $_SESSION['username'] = $username;
                $_SESSION['name'] = $row->CustomerName;
                $_SESSION['access'] = time();
            } else {
                $message = "Invalid Login";
                $data = array("message"=>$message);
                $this->output->set_status_header('500');
                $this->load->view('error_view',$data);
            }
        }
    }

    public function logout(){
        session_start();
        session_destroy();
    }
}
?>