    <?php
// getproduct.php
include 'connect.php';

// Check if POST data is received
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    // Check if 'pid' property is set
    if (isset($request->pid)) {
        $response = new stdClass();
        $imageurl = "http://localhost/assets/images/";

        // Prepare SQL query
        $query = "SELECT p.Barcode, p.CatID, p.Prod_name, p.Brand, p.Size, p.Price, p.Description, CONCAT('$imageurl', p.imgFile) AS img 
                  FROM product AS p 
                  WHERE p.Barcode = ?";

        // Prepare and bind parameters
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $request->pid);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = array();

        // Fetch results
        while ($row = $result->fetch_object()) {
            array_push($data, $row);
        }

        $stmt->close();

        // Return JSON response
        echo json_encode($data);
    } else {
        // Handle case where 'pid' property is not set
        echo json_encode(array("error" => "pid property is not set"));
    }
} else {
    // Handle case where request method is not POST
    echo json_encode(array("error" => "Invalid request method"));
}
?>
