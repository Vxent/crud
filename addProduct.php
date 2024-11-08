<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start the session for notifications
session_start();

// Database connection
include 'db_connection.php';

// Get data from the form
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$size = $_POST['size'];

// Handle image upload
$image_url = '';
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/"; // The uploads directory
    $target_file = $target_dir . basename($_FILES['image']['name']);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    // Debugging: Print file information
    echo "File Name: " . $_FILES['image']['name'] . "<br>";
    echo "File Type: " . $_FILES['image']['type'] . "<br>";
    echo "File Size: " . $_FILES['image']['size'] . "<br>";
    echo "Error Code: " . $_FILES['image']['error'] . "<br>";

    // Check if image file is a valid image
    $check = getimagesize($_FILES['image']['tmp_name']);
    if ($check !== false) {
        // Check file type
        if (in_array($imageFileType, ['jpg', 'jpeg', 'png']) || 
            in_array($_FILES['image']['type'], ['image/jpeg', 'image/png'])) {
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $image_url = $target_file; // Save the path for the database
                echo "File uploaded successfully: " . $image_url . "<br>";
            } else {
                echo "Sorry, there was an error uploading your file.";
                echo "Upload Error Code: " . $_FILES['image']['error'] . "<br>"; // Debugging line
            }
        } else {
            echo "Sorry, only JPG, JPEG, & PNG files are allowed.";
            echo "File type: " . $imageFileType . "<br>"; // Debugging line
        }
    } else {
        echo "File is not an image.";
    }
} else {
    echo "No file uploaded or an error occurred: " . $_FILES['image']['error'];
}

// Check the image URL before insertion
if ($image_url) {
    echo "Image URL before insert: " . $image_url . "<br>";
} else {
    echo "Image URL is empty. Upload may have failed.";
}

// Prepare and bind
$stmt = $db->prepare("INSERT INTO products (name, description, price, size, image_url) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdsd", $name, $description, $price, $size, $image_url); // Added size to the bind parameters

// Execute the query
if ($stmt->execute()) {
    // Set session message for successful creation
    $_SESSION['message'] = "New product added successfully.";
} else {
    echo "Error: " . $stmt->error;
}

// Close connections
$stmt->close();
$db->close();

// Redirect back to myProducts.php (or wherever you want)
header("Location: myProducts.php");
exit();
?>
