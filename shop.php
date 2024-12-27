<?php 
    include './Scripts/db_connect_sql.php';  // Include your database connection script

    // Fetch products from the database
    $sql = "SELECT * FROM products";  // Assuming 'products' is the table name
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // If there are products in the database
        $products = $result->fetch_all(MYSQLI_ASSOC);
    } else {
        $products = [];
    }

    // Close the database connection
    $conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - Samuel Okundalaiye</title>
    <link rel="stylesheet" href="./Styles/index.css">
    <link rel="stylesheet" href="./Styles/shop.css">
</head>
<body>
    <header class="header">
        <nav class="navbar">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="shop.php">Shop</a></li>
            </ul>
        </nav>
    </header>

    <main id="shop">
        <!-- Shop Hero Section -->
        <section class="shop-hero">
            <h1>Welcome to My Shop</h1>
            <p>Explore a collection of curated products and request them for purchase.</p>
            <button class="add-product"> Add Product</button>
        </section>

        <!-- Products Section -->
        <section id="products" class="products-section">
            <h2>Featured Products</h2>
            <?php if (count($products) > 0): ?>
                <div class="products-grid">
                    <?php foreach ($products as $product): ?>
                        <div class="product-item" data-product-id="<?= htmlspecialchars($product['id']) ?>">
                            <img src="./assets/uploads/<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                            <h3><?= htmlspecialchars($product['name']) ?></h3>
                            <p>Price: $<?= htmlspecialchars($product['price']) ?></p>
                            <strong><i><?= htmlspecialchars($product['description'] ?? 'No Description Available') ?></i></strong>
                            <div class="create-btns">
                                <button class="delete-btn">Delete</button>
                                <button class="buy">Purchase</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="no-product">
                        <p>No product created yet...</p>
                    </div>
                <?php endif; ?>
        </section>

    </main>

    <!-- Create Form (Initially Hidden) -->
    <section id="create-form-section" class="form-section">
            <h3 class="form-title">Create a Product</h3>
            <form id="create-form" enctype="multipart/form-data">
                <label for="product-name">Product Name:</label>
                <input type="text" id="product-name" name="product-name" required placeholder="Enter product name">

                <label for="product-description">Product Description:</label>
                <textarea id="product-description" name="product-description" required placeholder="Enter product description"></textarea>

                <label for="product-price">Price ($):</label>
                <input type="number" id="product-price" name="product-price" required placeholder="Enter price">

                <label for="product-image">Product Image:</label>
                <input type="file" id="product-image" name="product-image" accept="image/*" required>

                <div id="image-preview-container">
                    <img id="image-preview" src="" alt="Image Preview" style="display: none;">
                </div>

                <button type="submit">Create Product</button>
                <button type="button" id="cancel-purchase">Cancel</button>
            </form>
        </section>

    <!-- Purchase Form (Initially Hidden) -->
    <section id="purchase-form-section" class="form-section">
            <h3 class="form-title">Complete Your Purchase</h3>
            <form id="purchase-form">
                <label for="customer-name">Full Name:</label>
                <input type="text" id="customer-name" required placeholder="Enter your full name">
                <label for="customer-email">Email Address:</label>
                <input type="email" id="customer-email" required placeholder="Enter your email">
                <label for="shipping-address">Shipping Address:</label>
                <textarea id="shipping-address" required placeholder="Enter your shipping address"></textarea>
                <label for="product-name">Product:</label>
                <input type="text" id="product-name" disabled>
                <label for="product-price">Price:</label>
                <input type="text" id="product-price" disabled>
                <button type="submit">Confirm Purchase</button>
                <button type="button" id="cancel-purchase">Cancel</button>
            </form>
        </section>
        
        <footer class="footer">
            <p>&copy; 2024 Samuel Okundalaiye. All Rights Reserved.</p>
        </footer>
        <script src="./Scripts/shop.js"></script>

        <!-- Image Preview for create Form -->
        <script >
            document.getElementById('product-image').addEventListener('change', function(event) {
                const file = event.target.files[0]; // Get the selected file
                const preview = document.getElementById('image-preview');
                
                if (file) {
                    // Create a URL for the selected file
                    const reader = new FileReader();
                    
                    reader.onload = function(e) {
                        preview.src = e.target.result; // Set the preview image source
                        preview.style.display = 'block'; // Show the preview
                    };

                    reader.readAsDataURL(file); // Read the file as a data URL
                } else {
                    preview.style.display = 'none'; // Hide the preview if no file is selected
                }
            });
        </script>
</body>
</html>
