<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Categories_model extends CI_Model{
    function __construct()
    {
        parent::__construct();
    }

    function getBrands(){
        $query = $this->db->get('Brands');
        return $query->result();
    }

    function getSystems(){
        $query = $this->db->get('OperatingSystems');
        return $query->result();
    }

}
?>