<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Fetch products from the database, including the stock column
$query = "SELECT id, name, description, price, image_url, stock FROM products";
$result = $db->query($query);

// Initialize filter variables
$searchTerm = $_GET['search'] ?? '';
$categoryFilter = $_GET['category'] ?? 'all';

// Filter products based on search and category
$filteredProducts = [];
while ($product = $result->fetch_assoc()) {
    $title = strtolower($product['name']);
    $description = strtolower($product['description']);
    
    // Check if the product matches the search term
    $matchesSearch = (stripos($product['name'], $searchTerm) !== false || stripos($product['description'], $searchTerm) !== false);

    // Apply category filter if not 'all'
    if ($categoryFilter != 'all' && (strpos($title, $categoryFilter) === false && strpos($description, $categoryFilter) === false)) {
        continue; // Skip this product if it doesn't match the selected category
    }

    // Only add the product if it matches the search term and category filter
    if ($matchesSearch) {
        $filteredProducts[] = $product;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kween P Sports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/headlogo.png" type="image/x-icon">
    <link rel="stylesheet" href="style.css">

  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


    <style>
        .sold-out {
            position: relative;
            opacity: 0.5; /* Grayed out effect */
        }

        .sold-out::before {
            content: 'SOLD OUT';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-30deg);
            font-size: 2rem;
            color: red;
            opacity: 0.7;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <nav class="bg-black shadow-md top-0 left-0 w-full z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-2">
                <div class="flex-1 flex justify-start">
                    <div class="hidden md:flex space-x-4 p-2">
                        <a href="index.php" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Home</a>
                        <a href="#about" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">About</a>
                        <a href="#threats" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Services</a>
                    </div>
                </div>
                <div class="flex-1 flex justify-center">
                    <div class="text-center">
                        <img src="images/logo1.png" alt="" width="200px" class="h-20">
                    </div>
                </div>
                <div class="flex-1 flex justify-end">
                    <div class="hidden md:flex space-x-4 p-2">
                        <a href="contacts.html" class="text-white tracking-wider px-4 xl:px-8 py-2 text-lg hover:underline">Contacts</a>
                        <a href="order_history.php" class="text-gray-700 px-2 py-1 font-abhaya-libre uppercase text-white tracking-wider px-4 xl:px-8 py-2 text-sm hover:underline">Order History</a>
                        <a href="logout.php"><button type="submit" class="block font-bold bg-orange-400 text-white py-2 px-6 rounded hover:bg-orange-300 transition">Logout</button></a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <h1 class="text-2xl text-center mt-5 mb-5 font-bold text-orange-700">READYMADE JERSEYS</h1>

    <!-- Category Filter -->
    <div class="flex justify-center mb-5">
        <form method="GET" class="flex space-x-4">
        <div class="search-container" style="position: relative;">
    <input type="text" name="search" placeholder="Search..." class="border p-2 rounded pl-8" value="<?php echo htmlspecialchars($searchTerm); ?>">
    <i class="fas fa-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%);"></i>
</div>


            <!-- Category filter buttons -->
            <div class="flex space-x-2">
                <button type="submit" name="category" value="all" class="bg-yellow-500 text-white px-4 py-2 rounded">All</button>
                <button type="submit" name="category" value="jersey" class="bg-yellow-500 text-white px-4 py-2 rounded">Jersey</button>
                <button type="submit" name="category" value="short" class="bg-yellow-500 text-white px-4 py-2 rounded">Short</button>
                <button type="submit" name="category" value="tshirt" class="bg-yellow-500 text-white px-4 py-2 rounded">Tshirt</button>
                <button type="submit" name="category" value="warmer" class="bg-yellow-500 text-white px-4 py-2 rounded">Warmer</button>
            </div>
        </form>
    </div>

    <!-- Products Display -->
    <div class="flex flex-wrap justify-start">
        <?php foreach ($filteredProducts as $product): ?>
            <div class="w-full md:w-1/2 lg:w-1/4 p-4">
                <div class="border p-4 bg-white rounded-lg shadow-lg <?php echo $product['stock'] === 0 ? 'sold-out' : ''; ?>">
                    <h5 class="w-full h-10 object-cover rounded-t-lg font-bold text-center uppercase"><?php echo htmlspecialchars($product['name']); ?></h5>
                    <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="Product Image" class="w-full h-48 object-cover rounded-t-lg">
                    <p class="text-sm font-bold text-center text-gray-700"><?php echo htmlspecialchars($product['description']); ?></p>
                    <p class="text-xl font-bold text-center text-gray-700">₱ <?php echo htmlspecialchars($product['price']); ?></p>
                    <div class="flex justify-center mt-2">
                        <?php if ($product['stock'] > 0): ?>
                            <a href="orderForm.php?product_id=<?php echo $product['id']; ?>&action=buy">
                                <button class="bg-yellow-500 text-white px-4 py-2 rounded-lg">BUY NOW</button>
                            </a>
                        <?php else: ?>
                            <button class="bg-gray-500 text-white px-4 py-2 rounded-lg" disabled>SOLD OUT</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <footer class="bg-black text-white p-8">
        <div class="container mx-auto">
            <div class="flex justify-between">
                <div>
                    <h2 class="text-lg font-bold">Services</h2>
                    <ul>
                        <li><a href="#" class="hover:underline">Web Development</a></li>
                        <li><a href="#" class="hover:underline">Graphic Design</a></li>
                        <li><a href="#" class="hover:underline">SEO Services</a></li>
                    </ul>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Contact</h2>
                    <p>Email: <a href="mailto:info@example.com" class="hover:underline">info@example.com</a></p>
                </div>
                <div>
                    <h2 class="text-lg font-bold">Follow Us</h2>
                    <div class="flex space-x-4">
                        <a href="https://facebook.com" target="_blank" class="hover:text-blue-600">Facebook</a>
                        <a href="https://instagram.com" target="_blank" class="hover:text-purple-600">Instagram</a>
                        <a href="https://twitter.com" target="_blank" class="hover:text-blue-400">Twitter</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
