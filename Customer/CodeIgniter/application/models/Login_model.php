<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function getUser($username, $pwd){
        $sql = "select * from Customer where Username = ? and Password = password(?)";
        return $this->db->query($sql,array("$username","$pwd"));
    }
}
?>