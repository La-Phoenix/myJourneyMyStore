<?php
// Include database connection
include './db_connect_sql.php';

// Check if the request method is POST and if a file is uploaded
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if all required fields are present in the form data
    $productName = $_POST['product-name'] ?? null;
    $productDescription = $_POST['product-description'] ?? null;
    $productPrice = $_POST['product-price'] ?? null;

    // Check if the product image file is uploaded
    if (isset($_FILES['product-image']) && $_FILES['product-image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['product-image']['tmp_name'];
        $fileName = $_FILES['product-image']['name'];
        $fileSize = $_FILES['product-image']['size'];
        $fileType = $_FILES['product-image']['type'];

        // Define the upload directory (make sure this exists on your server)
        $uploadDir = '../assets/uploads/';  // Adjust this as needed
        $destPath = $uploadDir . basename($fileName);

        // Move the file to the desired directory
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // File uploaded successfully, now save product info in the database

            // SQL query to insert product details into the database
            $query = "INSERT INTO products (name, description, price, image_path) VALUES (?, ?, ?, ?)";

            if ($stmt = $conn->prepare($query)) {
                // Bind the form data to the query
                $stmt->bind_param("ssss", $productName, $productDescription, $productPrice, $fileName);

                // Execute the query
                if ($stmt->execute()) {
                    // Return success response
                    echo json_encode(['status' => 'success', 'message' => 'Product created successfully!']);
                } else {
                    // Return failure response
                    echo json_encode(['status' => 'error', 'message' => 'Error: ' . $stmt->error]);
                }

                $stmt->close();
            } else {
                // Return failure response
                echo json_encode(['status' => 'error', 'message' => 'Error preparing query: ' . $conn->error]);
            }
        } else {
            // Return failure response
            echo json_encode(['status' => 'error', 'message' => 'Error uploading the image. Please try again.']);
        }
    } else {
        // Return failure response if no image is uploaded
        echo json_encode(['status' => 'error', 'message' => 'No image uploaded or error in file upload.']);
    }
} else {
    // Method not allowed response
    http_response_code(405); // Method not allowed
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
}


?>
