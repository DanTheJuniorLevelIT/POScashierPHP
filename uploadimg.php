<?php
include 'connect.php';

$rid = $_POST['RemitanceID'];
$imgurl = "http://localhost/nlahPOS2/img/";

$response = new stdClass();
if (!empty($_FILES['files'])) {
    $path = $_FILES['files']['name'];
    $expname = explode('.', $path);
    $newpath = $expname[0].$rid.".".$expname[1];
    $ext = pathinfo($newpath, PATHINFO_EXTENSION);
        $targetDir = "./img/";
        $targetFilePath = $targetDir . $newpath;

    if (move_uploaded_file($_FILES['files']['tmp_name'], $targetFilePath)) {
        $query = "UPDATE remittance SET remImg='$newpath' WHERE RemittanceID='$rid'";
        if($result = $conn->query($query)){
            $response->msg = "success";
        }else{
            $response->msg = "0";
        }
        $response->msg = "Not Successful";
    } 
} 

echo json_encode($response);
?>