<?php
// Include necessary files and headers
include 'connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if required parameters are set
    if (isset($_POST['return'], $_POST['replace'])) {
        // Sanitize input
        $barcodereturn = mysqli_real_escape_string($conn, $_POST['return']);
        $barcodereplace = mysqli_real_escape_string($conn, $_POST['replace']);
        
        // Query to search for products with the received barcodes
        $sql = "SELECT * FROM product WHERE Barcode = '$barcodereturn' OR Barcode = '$barcodereplace'";
        $result = $conn->query($sql);

        if ($result) {
            if ($result->num_rows > 0) {
                $products = array();
                while ($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                // Return the found products as JSON response
                echo json_encode($products);
            } else {
                // Return error if no products found
                echo json_encode(array('error' => 'Product not found'));
            }
        } else {
            // Return error for database query error
            echo json_encode(array('error' => 'Database query error: ' . $conn->error));
        }
    } else {
        // Return error if any required parameter is missing
        echo json_encode(array('error' => 'Invalid request: Missing parameters.'));
    }
} else {
    // Return error for invalid request method
    echo json_encode(array('error' => 'Invalid request method.'));
}
?>
