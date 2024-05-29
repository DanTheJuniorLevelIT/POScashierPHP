<?php

include 'connect.php';

// Get today's date
$today = date("Y-m-d");

// Prepare SQL query to fetch transaction details for customer with CustID = 1 for today
$query = "SELECT td.*, p.Prod_name AS Name, p.Size, p.Price, t.Date AS TransactionDate
          FROM transactiondetails td 
          INNER JOIN transaction t ON td.transactionID = t.transactionID 
          INNER JOIN product p ON td.Barcode = p.Barcode
          WHERE t.CustID = 1 AND t.Date >= '$today 00:00:00' AND t.Date <= '$today 23:59:59'
          ORDER BY t.Date";

// Execute the query
$result = $conn->query($query);

// Initialize arrays to store transaction details and calculate total sales
$transactionDetails = [];
$totalSales = 0;

// Check if there are any rows returned by the query
if ($result && $result->num_rows > 0) {
    // Loop through each row of the result set
    while ($row = $result->fetch_assoc()) {
        // Add transaction details to the array
        $transactionDetails[] = $row;
        // Calculate total sales by multiplying price and quantity of each item
        $totalSales += $row['Price'] * $row['quantity'];
    }
} else {
    // No transaction details found for the customer with CustID = 1 for today
    echo json_encode(array("error" => "No transaction details found for the customer with CustID = 1 for today"));
}

// Close the database connection
$conn->close();

// Encode the result as JSON and output it
echo json_encode(array("transactionDetails" => $transactionDetails, "totalSales" => $totalSales));
?>
