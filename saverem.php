<?php
    include 'connect.php';
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    // Get the current date and time in MySQL format
    $currentDate = date('Y-m-d H:i:s');
    // Assuming RemAmount is the amount you want to insert
    $amount = $request->RemAmount;

    $query = "INSERT INTO remitance (Amount, Date) VALUES ('$amount', '$currentDate')";
    $result = $conn->query($query);
    
    if ($result) {
        $msg = "Success";
    } else {
        $msg = "Duplicate Entry";
    }

    echo json_encode($msg);

    //sa database ng remitance == ALTER TABLE remitance ADD CONSTRAINT unique_date UNIQUE (Date);

?>
