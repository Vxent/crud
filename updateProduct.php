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

// Check if ID is set in URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch product details
    $stmt = $db->prepare("SELECT name, description, price, size, image_url FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if product exists
    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();
    } else {
        // Redirect or show an error if no product is found
        $_SESSION['message'] = "Product not found.";
        header("Location: myProducts.php");
        exit();
    }
} else {
    // Redirect if no ID is provided
    $_SESSION['message'] = "Invalid product ID.";
    header("Location: myProducts.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kween P Sports</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="logo1.png" type="image/x-icon">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Red+Hat+Display:wght@500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body style="background-image: url('http://www.pixelstalk.net/wp-content/uploads/2016/10/Black-and-Orange-Background-Full-HD.jpg'); background-size: cover; background-repeat: no-repeat;" class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div  class="bg-gray-100 p-8 rounded-lg shadow-md w-full max-w-lg">
        <h1 class="text-3xl font-bold mb-6 text-center">Update Product</h1>
        <form action="updateProductProcess.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($product['image_url']); ?>">

            <div class="mb-4">
                <label class="block text-gray-700">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700">Description:</label>
                <textarea name="description" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400"><?php echo htmlspecialchars($product['description']); ?></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-gray-700">Price:</label>
                <input type="number" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" step="0.01" required class="w-full px-4 py-2 mt-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>
            
            <div class="mb-4">
    <span class="block text-sm font-medium text-gray-700">Size:</span>
    <div class="flex space-x-4 mt-2">
        <label class="flex items-center"><input type="radio" name="size" value="S" <?php if ($product['size'] === 'S') echo 'checked'; ?> class="mr-2" /> S</label>
        <label class="flex items-center"><input type="radio" name="size" value="M" <?php if ($product['size'] === 'M') echo 'checked'; ?> class="mr-2" /> M</label>
        <label class="flex items-center"><input type="radio" name="size" value="L" <?php if ($product['size'] === 'L') echo 'checked'; ?> class="mr-2" /> L</label>
        <label class="flex items-center"><input type="radio" name="size" value="XL" <?php if ($product['size'] === 'XL') echo 'checked'; ?> class="mr-2" /> XL</label>
        <label class="flex items-center"><input type="radio" name="size" value="XXL" <?php if ($product['size'] === 'XXL') echo 'checked'; ?> class="mr-2" /> XXL</label>
    </div>
</div>

            
            <div class="mb-4">
                <label class="block text-gray-700">Image Upload (JPEG/PNG):</label>
                <input type="file" name="image" accept=".jpeg,.jpg,.png" class="block w-full text-gray-700 border rounded-md file:mt-2 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                <p class="mt-2 text-gray-600 text-sm">Leave this blank if you do not want to change the image.</p>
            </div>
            
            <div class="flex justify-center">
                <button type="submit" class="px-6 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-blue-400 mr-2">Update Product</button>
                <a href="myProducts.php"><button  class="px-6 py-2 text-white bg-red-600 rounded-md ml-2 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-blue-400">Cancel Update</button></a>
            </div>
        </form>
    </div>
</body>
</html>