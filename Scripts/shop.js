// Get all the purchase buttons and purchase form section
const purchBtns = document.querySelectorAll('.buy');
const addBtn = document.querySelector('.add-product');
const purchFormSection = document.getElementById('purchase-form-section');
const createFormSection = document.getElementById('create-form-section');
const purchForm = document.getElementById('purchase-form');
const createForm = document.getElementById('create-form');
const cancelPurchButton = document.getElementById('cancel-purchase');
const prodNameField = document.getElementById('product-name');
const prodPriceField = document.getElementById('product-price');
const shopMain = document.querySelector("#shop");
const formTitle = document.querySelector(".form-title");
const deleteBtns = document.querySelectorAll(".delete-btn")



const showCreateForm = () => {
    shopMain.style.display = 'none';
    createFormSection.style.display = 'block'
}

addBtn.addEventListener('click', (event) => {
    setInterval(() => {
        showCreateForm();
    })
})
// Function to show the purchase form with product details
const showPurchaseForm = (productName, productPrice) =>  {
    prodNameField.value = productName;
    prodPriceField.value = `$${productPrice}`;
    setInterval(() => {
        purchFormSection.style.display = 'block';
        shopMain.style.display = 'none';
    }, 1000)

}

// Delete Product
deleteBtns.forEach(deleteBtn => {
    deleteBtn.addEventListener('click', (event) => {
        // Show confirmation dialog
        const confirmDeletion = confirm("Are you sure you want to delete this product?");
        
        if (confirmDeletion) {
            // If the user clicks "OK" (Yes)
            const productItem = deleteBtn.closest('.product-item');
            const productId = productItem.dataset.productId; 
            if (!productId) {
                alert("Product ID is missing for the selected item.");
                return;
            }

            if (confirmDeletion) {
                fetch('http://localhost:8000/Scripts/delete_product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: productId }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Product deleted successfully.");
                        productItem.remove(); // Remove the product from the DOM
                    } else {
                        alert("Failed to delete the product. Please try again.");
                    }
                })
                .catch(error => {
                    console.error("Error deleting product:", error);
                    alert("An error occurred. Please try again.");
                });
    
        } else {
            // If the user clicks "Cancel" (No)
            console.log("Deletion canceled.");
            // Do nothing, just return
            return;
        }
    }})
})

// Event listeners for each purchase button
purchBtns.forEach(btn => {
    btn.addEventListener('click', (event) => {
        const productItem = btn.closest('.product-item');
        const productName = productItem.getAttribute('data-product');
        const productPrice = productItem.getAttribute('data-price');
        
        showPurchaseForm(productName, productPrice);
    });
});

// Cancel button action to hide the form
cancelPurchButton.addEventListener('click', () => {
    purchForm.reset();
    // purchFormSection.style.display = 'block';
    // shopMain.style.display = 'none';
    window.location.href = 'http://localhost:8000/shop.php';
});

// create product
createForm.addEventListener('submit', (event) => {
    event.preventDefault();  // Prevent default form submission

    const form = event.target;
    const formData = new FormData(form);

    fetch('http://localhost:8000/Scripts/create_product.php', {
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);  // Log the response for debugging
        if (data.status === 'success') {
            alert('Product created successfully!');
            form.reset();
            document.getElementById('image-preview').style.display = 'none';
            window.location.href = 'http://localhost:8000/shop.php';
        } else {
            alert('Error: ' + data.message);  // Display the error message from PHP
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An unexpected error occurred.');
    });

})

// Handle form submission
purchForm.addEventListener('submit', (event) => {
    event.preventDefault();  // Prevent default form submission

    const customerName = document.getElementById('customer-name').value;
    const customerEmail = document.getElementById('customer-email').value;
    const shippingAddress = document.getElementById('shipping-address').value;

    // Display confirmation or process the data further
    alert(`Thank you for your purchase, ${customerName}! A confirmation has been sent to ${customerEmail}.`);

    // Hide the purchase form after submission
    purchFormSection.style.display = 'none';

    // Reset the form fields
    purchForm.reset();
});