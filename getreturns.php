<?php
    include 'connect.php';
    
    $query = "SELECT r.returnid,
        c.CustID,
        c.Lastname,
        c.Firstname,
        p.Prod_name AS returned_product,
        p2.Prod_name AS replacement_product,
        DATE_FORMAT(r.return_date, '%M %d, %Y') AS return_date
        FROM returns r
        JOIN customer c ON r.CustID = c.CustID
        JOIN product p ON r.returnprod = p.Barcode
        JOIN product p2 ON r.replacementprod = p2.Barcode
        WHERE DATE(r.return_date) = CURDATE()
        ORDER BY r.return_date;
    ";
    $result = $conn->query($query);
    
    $data = array();
    
    while ($row = $result->fetch_object()) {
        array_push($data, $row);
    }
    
    echo json_encode($data);
    
?>