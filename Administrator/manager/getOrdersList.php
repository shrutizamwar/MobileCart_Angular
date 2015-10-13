<?php
    require 'verifyManager.php';
    $conn = mysql_connect('cs-server.usc.edu:4353','root','admin');
    if(!$conn){
        die("Could not connect". mysql_error());
    }
    mysql_select_db("website",$conn);

    date_default_timezone_set('America/Los_Angeles');
    $today = date('Y-m-d');

    $byDate = filter_input(INPUT_POST,'byDate',FILTER_SANITIZE_STRING);
    $from = filter_input(INPUT_POST,'from',FILTER_SANITIZE_STRING);
    $to = filter_input(INPUT_POST,'to',FILTER_SANITIZE_STRING);
    $sumBy = filter_input(INPUT_POST,'sumBy',FILTER_SANITIZE_STRING);
    $groupBy = filter_input(INPUT_POST,'groupBy',FILTER_SANITIZE_STRING);
    $orderBy = filter_input(INPUT_POST,'orderBy',FILTER_SANITIZE_STRING);

    $productOSID = filter_input(INPUT_POST,'productOSID',FILTER_SANITIZE_STRING);
    $productBrandID = filter_input(INPUT_POST,'productBrandID',FILTER_SANITIZE_STRING);
    $productID = filter_input(INPUT_POST,'productID',FILTER_SANITIZE_STRING);
    $saleProductID = filter_input(INPUT_POST,'saleProductID',FILTER_SANITIZE_STRING);

    $todayDateSql = "and OrderDate = '".$today."'";
    $dateRangeSql = "and OrderDate between '".$from."' and '". $to."'";
    if($sumBy == 0){
        $sql = "select sum(OrderTotal) as TotalSales,sum(OrderQuantity) as TotalQuantity from Orders,OrderItems where Orders.OrderID = OrderItems.OrderID ";
        if($byDate == 1){
            $sql.=$todayDateSql;
        }
        elseif($byDate == 2){
            $sql.=$dateRangeSql;
        }
        $res = mysql_query($sql);
        $totalSales = array();
        if($row = mysql_fetch_assoc($res)){
            $totalSale = array(
                "TotalSales"=>$row["TotalSales"],
                "TotalQuantity" =>$row["TotalQuantity"]
            );
            $totalSales[]=$totalSale;
            echo json_encode($totalSales);
        }

    }

    elseif($groupBy == 0){
        $sql = "select Orders.OrderID as orderID,Orders.OrderTotal as OrderTotal, sum(OrderItems.OrderQuantity) as TotalQuantity,Orders.OrderDate as orderDate from Orders,OrderItems where Orders.OrderID = OrderItems.OrderID ";
        if($byDate == 1){
            $sql .= $todayDateSql;
        }
        elseif($byDate == 2){
            $sql .= $dateRangeSql;
        }

        $sql.=" group by(Orders.OrderID) order by ";
        if($sumBy == 1){
            if($orderBy == 0) {
                 $sql.=" TotalQuantity ";
            }
            elseif($orderBy == 1){
                $sql .= " TotalQuantity DESC";
            }
        }
        elseif($sumBy == 2){
            if($orderBy == 0) {
                $sql.=" OrderTotal";
            }
            elseif($orderBy==1){
                $sql .= " OrderTotal DESC";
            }
        }
        $res = mysql_query($sql);
        $orders = array();
        while($row = mysql_fetch_assoc($res)){
           $order= array(
               "OrderID" => $row['orderID'],
               "OrderTotal" => $row['OrderTotal'],
               "TotalQuantity" => $row['TotalQuantity'],
               "OrderDate"=>$row['orderDate']
           );
            $orders[]=$order;
        }
        echo json_encode($orders);
    }

    else{
        if($groupBy == 1){
            $sql = "select s.SystemID, s.SystemName, sum(OrderQuantity) as Quantity, sum(oi.OrderQuantity * oi.MobilePrice) as Total from Orders o ,OrderItems oi,Mobiles m,OperatingSystems s where o.OrderID = oi.OrderID and oi.MobileID = m.MobileID and m.SystemID = s.SystemID ";
            if($byDate == 1){
                $sql .= $todayDateSql;
            }
            elseif($byDate == 2){
                $sql .= $dateRangeSql;
            }
            if($productOSID == 000){
                $sql.= "group by(s.SystemID) order by ";
                if($sumBy == 1){
                    if($orderBy == 0) {
                        $sql.=" Quantity ";
                    }
                    elseif($orderBy == 1){
                        $sql .= " Quantity DESC";
                    }
                }
                elseif($sumBy == 2){
                    if($orderBy == 0) {
                        $sql.=" Total";
                    }
                    elseif($orderBy==1){
                        $sql .= " Total DESC";
                    }
                }
            }
            else{
                $sql.= " and s.SystemID = ".$productOSID." group by(s.SystemID)";
            }

            $res = mysql_query($sql);
            $results = array();
            while($row = mysql_fetch_assoc($res)){
                $result= array(
                    "SystemID" => $row['SystemID'],
                    "SystemName" => $row['SystemName'],
                    "TotalQuantity" => $row['Quantity'],
                    "TotalAmount"=>$row['Total']
                );
                $results[]=$result;
            }
            echo json_encode($results);
        }
        elseif($groupBy == 2){
            $sql = "select b.BrandID, b.BrandName, sum(OrderQuantity) as Quantity, sum(oi.OrderQuantity * oi.MobilePrice) as Total from Orders o ,OrderItems oi,Mobiles m,Brands b where o.OrderID = oi.OrderID and oi.MobileID = m.MobileID and m.BrandID = b.BrandID ";
            if($byDate == 1){
                $sql .= $todayDateSql;
            }
            elseif($byDate == 2){
                $sql .= $dateRangeSql;
            }
            if($productBrandID == 000){
                $sql.= " group by(b.BrandID) order by ";
                if($sumBy == 1){
                    if($orderBy == 0) {
                        $sql.=" Quantity";
                    }
                    elseif($orderBy == 1){
                        $sql .= " Quantity DESC";
                    }
                }
                elseif($sumBy == 2){
                    if($orderBy == 0) {
                        $sql.=" Total";
                    }
                    elseif($orderBy==1){
                        $sql .= " Total DESC";
                    }
                }
            }
            else{
                $sql.=" and b.BrandID =".$productBrandID." group by(b.BrandID) ";
            }
            $res = mysql_query($sql);
            $results = array();
            while($row = mysql_fetch_assoc($res)){
                $result= array(
                    "BrandID" => $row['BrandID'],
                    "BrandName" => $row['BrandName'],
                    "TotalQuantity" => $row['Quantity'],
                    "TotalAmount"=>$row['Total']
                );
                $results[]=$result;
            }
            echo json_encode($results);
        }
        elseif($groupBy == 3){
            $sql = "select m.MobileID, m.MobileName, sum(OrderQuantity) as Quantity, sum(oi.OrderQuantity * oi.MobilePrice) as Total from Orders o ,OrderItems oi,Mobiles m where o.OrderID = oi.OrderID and oi.MobileID = m.MobileID ";
            if($byDate == 1){
                $sql .= $todayDateSql;
            }
            elseif($byDate == 2){
                $sql .= $dateRangeSql;
            }
            if($productID == 000){
                $sql.=" group by(m.MobileID) order by ";
                if($sumBy == 1){
                    if($orderBy == 0) {
                        $sql.=" Quantity";
                    }
                    elseif($orderBy == 1){
                        $sql .= " Quantity DESC";
                    }
                }
                elseif($sumBy == 2){
                    if($orderBy == 0) {
                        $sql.=" Total";
                    }
                    elseif($orderBy==1){
                        $sql .= " Total DESC";
                    }
                }
            }
            else{
                $sql.=" and m.MobileID = ".$productID." group by(m.MobileID)";
            }
            $res = mysql_query($sql);
            $results = array();
            while($row = mysql_fetch_assoc($res)){
                $result= array(
                    "MobileID" => $row['MobileID'],
                    "MobileName" => $row['MobileName'],
                    "TotalQuantity" => $row['Quantity'],
                    "TotalAmount"=>$row['Total']
                );
                $results[]=$result;
            }
            echo json_encode($results);
        }
        elseif($groupBy == 4){
            $sql = "select s.SalesID, s.MobileID, m.MobileName, sum(OrderQuantity) as Quantity, sum(oi.OrderQuantity * oi.MobilePrice) as Total, s.StartDate, s.EndDate from Orders o, OrderItems oi, Mobiles m, Sales s where o.OrderID = oi.OrderID and oi.MobileID = m.MobileID and oi.SaleID = s.SalesID ";
            if($byDate == 1){
                $sql .= $todayDateSql;
            }
            elseif($byDate == 2){
                $sql .= $dateRangeSql;
            }
            if($saleProductID == 000){
                $sql.= " group by s.SalesID order by ";
                if($sumBy == 1){
                    if($orderBy == 0) {
                        $sql.=" Quantity";
                    }
                    elseif($orderBy == 1){
                        $sql .= " Quantity DESC";
                    }
                }
                elseif($sumBy == 2){
                    if($orderBy == 0) {
                        $sql.=" Total";
                    }
                    elseif($orderBy==1){
                        $sql .= " Total DESC";
                    }
                }
            }
            else{
                $sql.=" and s.SalesID = ".$saleProductID." group by(s.SalesID)";
            }

            $res = mysql_query($sql);
            $results = array();
            while($row = mysql_fetch_assoc($res)){
                $result= array(
                    "SaleID" => $row['SalesID'],
                    "MobileID" => $row['MobileID'],
                    "MobileName" => $row['MobileName'],
                    "TotalQuantity" => $row['Quantity'],
                    "TotalAmount"=>$row['Total'],
                    "StartDate"=>$row['StartDate'],
                    "EndDate"=>$row['EndDate']
                );
                $results[]=$result;
            }
            echo json_encode($results);
        }
    }
    mysql_close($conn);
?>