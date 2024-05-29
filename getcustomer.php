<?php
    include 'connect.php';
    // $imgurl = "http://localhost/AppdevprojectAdmin/media";
    $query = "SELECT * from customer";
    $result = $conn->query($query);

    // $row = $result-> fetch_object();
    $data = array();

    while($row = $result->fetch_object()){
        array_push($data,$row);
    }

    echo json_encode($data);
?>