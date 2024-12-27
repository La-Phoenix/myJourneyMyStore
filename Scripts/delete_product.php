<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the JSON body data
    $data = json_decode(file_get_contents('php://input'), true);

    // Extract the product ID
    $productId = $data['id'] ?? null;

    if ($productId) {
        // Database connection
        require './db_connect_sql.php'; // Include the database connection script

        // Prepare the SQL statement (for mysqli, using prepare and bind_param)
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->bind_param('i', $productId); // Bind the product ID as an integer parameter

        // Execute the query
        if ($stmt->execute()) {
            // Check if any rows were affected (deleted)
            if ($stmt->affected_rows > 0) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Product not found or already deleted']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete the product']);
        }

        // Close the statement
        $stmt->close();
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid product ID.']);
    }
} else {
    http_response_code(405); // Method not allowed
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}
?>
