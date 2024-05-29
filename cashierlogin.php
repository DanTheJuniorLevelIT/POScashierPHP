<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    // $query = "SELECT UserID, Email FROM user WHERE Email = '$request->use' AND Password=sha2('$request->pas',224) AND Role = 'Cashier'";
    $query = "SELECT UserID, Lastname, Firstname, Role, Contact FROM user WHERE Email = '$request->use' AND Password=sha2('$request->pas',224) AND Role = 'Cashier'";
    $result = $conn->query($query);
    
    if ($result->num_rows!=0){
        $row = $result->fetch_object();     
    }else{
        $row = 0;
    }

    echo json_encode($row);
?>