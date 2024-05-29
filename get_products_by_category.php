<?php
include 'connect.php';
if (isset($_GET['categoryId']) && is_numeric($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];

    // Fetch products by category
    $query = "SELECT Barcode,CatID,Prod_name,Brand,Size,Price,Description,imgFile FROM product WHERE CatID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $categoryId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result) {
        $products = array();

        while ($row = $result->fetch_assoc()) {
            // Modify the image file path
            $row['imgFile'] = "http://localhost/nlahPOS/img/" . $row['imgFile'];
            $products[] = $row;
        }

        echo json_encode($products);
    } else {
        echo json_encode(array('error' => 'Failed to fetch products'));
    }
} else {
    echo json_encode(array('error' => 'Invalid or missing Category ID'));
}
?>
