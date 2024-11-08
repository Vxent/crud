<?php 
session_start(); // Start the session at the beginning

// Database configuration
include 'db_connection.php';

// Initialize a variable for order count
$orderCount = 0; // Default to 0

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id']) && is_numeric($_POST['id'])) {
        $id = intval($_POST['id']);

        // Check for existing orders for this product
        $checkQuery = "SELECT COUNT(*) FROM orders WHERE product_id = ?";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bind_param("i", $id);
        $checkStmt->execute();
        $checkStmt->bind_result($orderCount);
        $checkStmt->fetch();
        $checkStmt->close();

        if ($orderCount > 0) {
            // Set message if deletion is not allowed
            $_SESSION['message'] = "Cannot delete product: There are existing orders associated with this product.";
        } else {
            // Prepare and execute the delete statement
            $deleteQuery = "DELETE FROM products WHERE id=?";
            $deleteStmt = $db->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $id);

            if ($deleteStmt->execute()) {
                $_SESSION['message'] = "Product deleted successfully!";
            } else {
                $_SESSION['message'] = "Error deleting product: " . $deleteStmt->error;
            }

            $deleteStmt->close();
        }
        
        // Redirect back to products page
        header('Location: myProducts.php');
        exit;
    } else {
        echo "Invalid product ID.";
    }
}

// Close the database connection
$db->close();
?>