<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function getProducts($brands, $systems, $searchTerm)
    {

        date_default_timezone_set('America/Los_Angeles');
        $today = date('Y-m-d');

        if (count($brands) > 0) {
            $brandIDArray = implode(" , ", $brands);
            $sql = "select * from Mobiles where BrandID in ($brandIDArray)";
            $otherSql = "select *from Mobiles m, Sales s where s.MobileID = m.MobileID and m.BrandID in ($brandIDArray) and '" . $today . "' between s.StartDate and s.EndDate";
        }

        if (count($systems) > 0) {
            $OSIDArray = implode(", ", $systems);
            $sql = "select * from Mobiles where SystemID in ($OSIDArray)";
            $otherSql = "select *from Mobiles m, Sales s where s.MobileID = m.MobileID and m.SystemID in ($OSIDArray) and '" . $today . "' between s.StartDate and s.EndDate";
        }
        if (count($brands) > 0 && count($systems) > 0) {
            $brandIDArray = implode(" , ", $brands);
            $OSIDArray = implode(", ", $systems);
            $sql = "select * from Mobiles where BrandID in ($brandIDArray) AND SystemID in ($OSIDArray)";
            $otherSql = "select *from Mobiles m, Sales s where s.MobileID = m.MobileID and m.BrandID in ($brandIDArray) AND m.SystemID in ($OSIDArray) and '" . $today . "' between s.StartDate and s.EndDate";
        }

        if (count($brands) == 0 && count($systems) == 0) {
            $sql = "select * from Mobiles";
            $otherSql = "select * from Sales as s,Mobiles as m where s.MobileID = m.MobileID and '" . $today . "' between s.StartDate and s.EndDate";
        }

        if (strlen($searchTerm) != 0) {
            if (count($brands) == 0 && count($systems) == 0) {
                $sql .= " where MobileName LIKE '%" . $searchTerm . "%'";
                $otherSql .= " and MobileName LIKE '%" . $searchTerm . "%'";
            } else {
                $searchSql = " and MobileName LIKE '%" . $searchTerm . "%'";
                $sql .= $searchSql;
                $otherSql .= $searchSql;
            }
        }

        $getMobileQuery = $this->db->query($sql)->result();
        $getSaleMobileQuery = $this->db->query($otherSql)->result();
        return array('mobileResult' => $getMobileQuery,'saleResult'=>$getSaleMobileQuery);
    }

    function getRegularMobileDetails($mobileID){
        $sql = "select * from Mobiles where MobileID =?";
        return $this->db->query($sql,array($mobileID));
    }

    function getSaleMobileDetail($mobileID,$saleID){

        $sql = "select * from Sales as s,Mobiles as m where s.MobileID = m.MobileID and s.MobileID =  ? and s.SalesID = ?";
        return $this->db->query($sql,array($mobileID,$saleID));
    }

    function getProductSuggestion($mobileID){
        date_default_timezone_set('America/Los_Angeles');
        $today = date('Y-m-d');
        $saleSql = "select * from ProductSuggestion p, Mobiles m, Sales s where p.OtherMobileID = m.MobileID and p.MobileID=? and s.MobileID=m.MobileID and ? between s.StartDate and s.EndDate order by p.Counter DESC LIMIT 5";
        $sql = "select * from ProductSuggestion p,Mobiles m where p.OtherMobileID = m.MobileID and p.MobileID=? order by p.Counter DESC LIMIT 5";

        $saleQuery = $this->db->query($saleSql,array($mobileID,"$today"))->result();
        $query = $this->db->query($sql,array($mobileID))->result();

        return array('mobileResult' => $query,'saleResult'=>$saleQuery);
    }
}
?>