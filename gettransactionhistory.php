<?php
include 'connect.php';

$sql = "SELECT DISTINCT DATE_FORMAT(`Date`, '%M %e, %Y') AS date 
        FROM transaction 
        ORDER BY `Date` DESC;
        "; // Using backticks around 'Date' to avoid conflicts with reserved keywords
        
$result = $conn->query($sql);

$dates = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $dates[] = $row['date'];
    }
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($dates);

$conn->close();
?>
