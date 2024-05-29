<?php
    include 'connect.php';

    $today = date('Y-m-d');

    // $query = "SELECT Barcode,CatID,Prod_name,Brand,Size,Price,Description,imgFile FROM product";
    $query = "SELECT p.Barcode, p.CatID, p.Prod_name, p.Brand, p.Size, p.Price, p.Description, p.imgFile
                FROM product p
                JOIN releaseproduct r ON p.Barcode = r.Barcode
                WHERE DATE(r.Release_Date) = '$today'";
    $result = $conn->query($query);

    $data = array();

    while($row = $result->fetch_object()){
        $row->imgFile = "http://localhost/nlahPOS/img/".$row->imgFile;
        array_push($data,$row);
    }

    echo json_encode($data);
?>