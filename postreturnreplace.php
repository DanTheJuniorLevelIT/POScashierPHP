<?php
include 'connect.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");

// Debug: Log received data
file_put_contents('debug.log', print_r($_POST, true)); // Log received POST data
echo "Script is executed!"; // Add a debug message

// Check if the request method is POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Decode JSON data from the request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Check if all required fields are present in the decoded data
    if (isset($data['CustID'], $data['returnprod'], $data['replacementprod'])) {
        // Extract data from the decoded JSON
        $CustID = $data['CustID'];
        $returnprod = $data['returnprod'];
        $replacementprod = $data['replacementprod'];

        // Prepare and bind SQL statement
        $sql = "INSERT INTO returns (CustID, returnprod, replacementprod, return_date) VALUES (?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss", $CustID, $returnprod, $replacementprod);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success message
            echo json_encode(array("message" => "Return data inserted successfully."));
        } else {
            // Return error message
            echo json_encode(array("error" => "Error: " . $stmt->error));
        }

        // Close statement
        $stmt->close();
    } else {
        // Return error if any field is missing
        echo json_encode(array("error" => "Please fill all fields."));
    }
} else {
    // Return error for invalid request method
    echo json_encode(array("error" => "Invalid request method."));
}

// Close database connection
$conn->close();
?>
