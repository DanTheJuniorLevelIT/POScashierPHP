<?php
include 'connect.php';

// Fetch categories
$query = "SELECT CatID, Type FROM category";
$result = $conn->query($query);

$categories = array();

while ($row = $result->fetch_assoc()) {
    array_push($categories, $row);
}

echo json_encode($categories);
?>
