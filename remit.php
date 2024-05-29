<?php
include 'connect.php';

$imgurl = "http://localhost/nlahPOS2/img/";

$query = "SELECT CONCAT('$imgurl', remImg) as img, RemittanceID, DATE_FORMAT(Date, '%M %d, %Y') AS Date, Amount FROM remittance ORDER BY Date DESC";
$result = $conn->query($query);

$data = array();

while($row = $result->fetch_object()){
    // $row->remImg = "http://localhost/nlahPOS2/img/" . $row->remImg;
    array_push($data, $row);
}

echo json_encode($data);
?>