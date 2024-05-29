<?php
include 'connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Function to check if customer has charges exceeding credit limit
function customerHasCharges($customerId, $conn, $totalAmount) {
    // Query to fetch customer charges and credit limit
    $sql = "SELECT charges, credit FROM customer WHERE CustID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customerId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $charges = $row['charges'];
        $credit = $row['credit'];

        // Calculate total charges (existing charges + new charges)
        $totalCharges = $charges + $totalAmount;

        // Check if total charges exceed credit limit
        if ($totalCharges > $credit) {
            return true; // Customer has insufficient balance
        }
    }
    
    return false; // Customer has sufficient balance
}

$data = json_decode(file_get_contents('php://input'), true);

// Check if customer ID and transactions are present in the received data
if(isset($data['customerId']) && isset($data['transactions'])) {
    $customerId = $data['customerId'];
    $transactions = $data['transactions'];

    // Calculate total amount for the transaction
    $totalAmount = 0;
    foreach ($transactions as $transaction) {
        $totalAmount += $transaction['Price'] * $transaction['quantity'];
    }

    // Check if the selected customer has charges exceeding credit limit
    if ($customerId != 1 && customerHasCharges($customerId, $conn, $totalAmount)) {
        // Show modal indicating insufficient balance
        echo json_encode(array("status" => "error", "message" => "Insufficient balance."));
        return; // Halt the transaction process
    }

    // Insert into the transaction table with the received customer ID
    $sqlTransaction = "INSERT INTO transaction (CustID, Date) VALUES (?, NOW())";
    $stmt = $conn->prepare($sqlTransaction);
    $stmt->bind_param("i", $customerId);
    if ($stmt->execute()) {
        $transactionId = $conn->insert_id;

        // Insert transaction details into transactiondetails table
        $sqlTransactionDetails = "INSERT INTO transactiondetails (transactionID, Barcode, Price, quantity) 
                                  VALUES (?, ?, ?, ?)";
        $stmtDetails = $conn->prepare($sqlTransactionDetails);
        $stmtDetails->bind_param("iidi", $transactionId, $barcode, $price, $quantity);
        foreach ($transactions as $transaction) {
            $barcode = $transaction['Barcode'];
            $price = $transaction['Price'];
            $quantity = $transaction['quantity'];
            $stmtDetails->execute();
        }

        // Update charges for customers (excluding "CASH")
        if ($customerId != 1) {
            $sqlUpdateCharges = "UPDATE customer SET charges = IFNULL(charges, 0) + ? WHERE CustID = ?";
            $stmtUpdateCharges = $conn->prepare($sqlUpdateCharges);
            $stmtUpdateCharges->bind_param("di", $totalAmount, $customerId);
            if ($stmtUpdateCharges->execute()) {
                echo json_encode(array("status" => "overall transaction inserted successfully."));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error updating charges: " . $stmtUpdateCharges->error));
            }
            
            $stmtUpdateCharges->close();
        } else {
            echo json_encode(array("status" => "overall transaction inserted successfully."));
        }

        // Close prepared statements
        $stmtDetails->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Error inserting transaction data: " . $stmt->error));
    }

    // Close connection and statement
    $stmt->close();
    $conn->close();
} else {
    echo json_encode(array("status" => "error", "message" => "Customer ID or transactions not found in the request."));
}
?>