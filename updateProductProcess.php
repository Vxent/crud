<?php
session_start();

// Database configuration
$host = 'localhost'; 
$username = 'root'; 
$password = ''; 
$database = 'capstoneloginver2'; 

// Create a connection
$db = new mysqli($host, $username, $password, $database);

// Check the connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $id = intval($_POST['id']);
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $size = $_POST['size']; // Get the selected size from the form
    $image_url = $_POST['current_image']; // Assuming you pass the current image URL in the form

    // Check if a new image is uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $target_dir = "uploads/";
        
        // Ensure the directory exists
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true); // Create the directory if it doesn't exist
        }

        $target_file = $target_dir . basename($_FILES["image"]["name"]);

        // Move the uploaded file to the desired directory
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $image_url = $target_file; // Update image_url with the new file path
        } else {
            $_SESSION['message'] = "Sorry, there was an error uploading your file.";
            header("Location: myProducts.php");
            exit();
        }
    }

    // Prepare and execute the update query
    $updateQuery = "UPDATE products SET name=?, description=?, price=?, size=?, image_url=? WHERE id=?";
    $stmt = $db->prepare($updateQuery);
    $stmt->bind_param("ssdssi", $name, $description, $price, $size, $image_url, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Product updated successfully!";
        header("Location: myProducts.php");
    } else {
        $_SESSION['message'] = "Error updating product: " . $db->error;
        header("Location: myProducts.php");
    }

    $stmt->close();
} else {
    // Redirect if the request method is not POST
    $_SESSION['message'] = "Invalid request.";
    header("Location: myProducts.php");
}

// Close the database connection
$db->close();
?>