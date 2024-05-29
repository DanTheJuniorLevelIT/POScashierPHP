<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['barcode'])) {
    $barcode = mysqli_real_escape_string($conn, $_POST['barcode']);
    $sql = "SELECT * FROM product WHERE Barcode = '$barcode'";
    $result = $conn->query($sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            echo json_encode($product);
        } else {
            echo json_encode(array('error' => 'Product not found'));
        }
    } else {
        echo json_encode(array('error' => 'Database query error: ' . mysqli_error($conn)));
    }
} else {
    echo json_encode(array('error' => 'Invalid request'));
}
?>
