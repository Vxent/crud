<?php
// connect to db 
include 'db_connection.php';
session_start(); // if something goes wrong here possibly delete this line of code

// Pagination variables
$products_per_page = 5; // Number of products per page
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

// Fetch total products for pagination
$total_query = "SELECT COUNT(*) as total FROM products";
$total_result = $db->query($total_query);
$total_row = $total_result->fetch_assoc();
$total_products = $total_row['total'];
$total_pages = ceil($total_products / $products_per_page);

// Fetch products with pagination
$query = "SELECT id, name, description, price, size, image_url, stock FROM products LIMIT $products_per_page OFFSET $offset";

$result = $db->query($query);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kween P Sports</title>
    <!-- tailwind css cdn -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="shortcut icon" href="images/headlogo.png" type="image/x-icon">
    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&family=Red+Hat+Display:wght@500;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Dots&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">

    <script>
        function closeMessage() {
            document.getElementById('message').style.display = 'none';
        }
    </script>
</head>
<style>
    @import url('https://fonts.googleapis.com/css?family=Karla:400,700&display=swap');

    .font-family-karla {
        font-family: karla;
    }

    .bg-sidebar {
        background: orange;
    }

    .cta-btn {
        color: black;
    }

    .upgrade-btn {
        background: black;
    }

    .upgrade-btn:hover {
        background: black;
    }

    .active-nav-link {
        background: grey;
    }

    .nav-item:hover {
        background: grey;
    }

    .account-link:hover {
        background: grey;
    }
</style>

<body class="bg-gray-100 font-family-karla flex">

    <aside class="relative bg-sidebar h-screen w-64 hidden sm:block shadow-xl">
        <div class="p-6">
            <img src="images/logo1.png" alt="">
        </div>
        <nav class="text-white text-base font-semibold pt-3">
            <a href="adminDashboard.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-tachometer-alt mr-3"></i>
                Dashboard
            </a>
            <a href="userOrder.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fas fa-sticky-note mr-3"></i>
                User Orders
            </a>
            <a href="myProducts.php" class="flex items-center active-nav-link text-white py-4 pl-6 nav-item">
                <i class="fas fa-table mr-3"></i>
                My Products
            </a>
            <a href="customizationOrder.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fa-solid fa-shirt mr-2"></i>
                Customized Order
            </a>
            <a href="adminChangePassword.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-4 pl-6 nav-item">
                <i class="fa fa-cog mr-3"></i>
                Change Password
            </a>

        </nav>

    </aside>

    <div class="relative w-full flex flex-col h-screen overflow-y-hidden">
        <!-- Desktop Header -->
        <header class="w-full items-center bg-white py-2 px-6 hidden sm:flex">
            <div class="w-1/2"></div>
            <div x-data="{ isOpen: false }" class="relative w-1/2 flex justify-end">
                <button @click="isOpen = !isOpen" class="realtive z-10 w-12 h-12 rounded-full overflow-hidden border-4 border-gray-400 hover:border-gray-300 focus:border-gray-300 focus:outline-none">
                    <img src="https://tse1.mm.bing.net/th?id=OIP.V0NH3fa-mZ4AJ94SEQTy_wHaHa&pid=Api&P=0&h=220">
                </button>
                <button x-show="isOpen" @click="isOpen = false" class="h-full w-full fixed inset-0 cursor-default"></button>
                <div x-show="isOpen" class="absolute w-32 bg-white rounded-lg shadow-lg py-2 mt-16">


                    <a href="logout.php" class="block px-4 py-2 account-link hover:text-white">Log out</a>
                </div>
            </div>
        </header>

        <!-- Mobile Header & Nav -->
        <header x-data="{ isOpen: false }" class="w-full bg-sidebar py-5 px-6 sm:hidden">
            <div class="flex items-center justify-between">
                <img src="images/logo1.png" alt="" class="text-center">
                <button @click="isOpen = !isOpen" class="text-white text-3xl focus:outline-none">
                    <i x-show="!isOpen" class="fas fa-bars"></i>
                    <i x-show="isOpen" class="fas fa-times"></i>
                </button>
            </div>

            <!-- Dropdown Nav -->
            <nav :class="isOpen ? 'flex': 'hidden'" class="flex flex-col pt-4">
                <a href="adminDashboard.php" class="flex items-center active-nav-link text-white py-2 pl-4 nav-item">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                <a href="userOrder.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-sticky-note mr-3"></i>
                    User Orders
                </a>
                <a href="myProducts.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-table mr-3"></i>
                    My Products
                </a>
                <a href="myProducts.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fa-solid fa-shirt mr-2"> </i>
                    Customized Order
                </a>
                <a href="adminChangePassword.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fa fa-fog mr-3"> </i>
                    Change Password
                </a>



                <a href="logout.php" class="flex items-center text-white opacity-75 hover:opacity-100 py-2 pl-4 nav-item">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Log Out</a>

            </nav>

        </header>

        <div class="w-full h-screen overflow-x-hidden border-t flex flex-col">
            <main class="w-full flex-grow p-6">
                <h1 class="text-3xl text-black pb-6">Create Products</h1>

                <div class="w-full mt-6" x-data="{ openTab: 1 }">
                    <div>
                        <!--VIEW PRODUCTS-->
                        <ul class="flex border-b">
                            <li class="-mb-px mr-1" @click="openTab = 1">
                                <a :class="openTab === 1 ? 'border-l border-t border-r rounded-t text-blue-700 font-semibold' : 'text-blue-500 hover:text-blue-800'" class="bg-white inline-block py-2 px-4 font-semibold" href="#">My Products</a>
                            </li>
                            <!-- CREATE PRODUCTS-->
                            <li class="mr-1" @click="openTab = 2">
                                <a :class="openTab === 2 ? 'border-l border-t border-r rounded-t text-blue-700 font-semibold' : 'text-blue-500 hover:text-blue-800'" class="bg-white inline-block py-2 px-4 font-semibold" href="#">Create Products</a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <div x-show="openTab === 1">
                            <div class="w-full mt-6">
                                <p class="text-xl pb-3 flex items-center">
                                    <i class="fas fa-list mr-2"></i> Product List
                                </p>
                                <div class="bg-white overflow-auto">
                                    <table class="min-w-full bg-white">
                                        <thead class="bg-gray-800 text-white">
                                            <tr>
                                                <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Name</th>
                                                <th class="w-1/3 text-left py-3 px-4 uppercase font-semibold text-sm">Description</th>
                                                <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Price</th>
                                                <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Size</th>
                                                <th class="w-1/5 text-left py-3 px-4 uppercase font-semibold text-sm">Status</th>
                                                <th class="w-1/5 text-center uppercase font-semibold text-sm p-5">Image</th>
                                                <th class="w-1/5 text-center py-3 px-4 uppercase font-semibold text-sm">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-700">
                                            <?php if ($result->num_rows > 0): ?>
                                                <?php while ($row = $result->fetch_assoc()): ?>
                                                    <tr>
                                                        <td class="w-1/3 text-left py-3 px-4"><?php echo htmlspecialchars($row['name']); ?></td>
                                                        <td class="w-1/3 text-left py-3 px-4"><?php echo htmlspecialchars($row['description']); ?></td>
                                                        <td class="text-left py-3 px-4"><?php echo htmlspecialchars($row['price']); ?></td>
                                                        <td class="text-left py-3 px-3"><?php echo htmlspecialchars($row['size']); ?></td>
                                                        <td class="text-left py-3 px-4 <?php echo $row['stock'] ? 'text-blue-500' : 'text-red-500'; ?>">
                                                            <?php echo $row['stock'] ? 'Available' : 'Sold Out'; ?>
                                                        </td>
                                                        <td class="text-center p-3">
                                                            <img src="<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="w-40 h-20">
                                                        </td>
                                                        <td>
                                                            <div class="md:flex space-x-2">
                                                                <a href="updateProduct.php?id=<?php echo $row['id']; ?>">
                                                                    <button class="bg-yellow-400 hover:bg-yellow-500 sm:px-4 sm:py-2 rounded">Update</button>
                                                                </a>
                                                                <form action="deleteProduct.php" method="POST" class="inline pr-3">
                                                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                                                    <button type="submit" class="bg-red-500 sm:px-4 hover:bg-red-600 sm:py-2 rounded">Delete</button>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="7" class="text-center py-3">No products found</td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php if (isset($_SESSION['message'])): ?>
                                                <div id="message" class="bg-red-500 text-white p-4 rounded relative mb-4">
                                                    <span><?php echo $_SESSION['message']; ?></span>
                                                    <button onclick="closeMessage()" class="absolute top-1 right-1 text-white hover:text-gray-200">
                                                        &times;
                                                    </button>
                                                </div>
                                                <?php unset($_SESSION['message']); // Clear the message after displaying 
                                                ?>
                                            <?php endif; ?>
                                        </tbody>

                                    </table>

                                </div>
                            </div>

                            <?php
                            // Close the database connection
                            $db->close();
                            ?>
                            <nav class="flex justify-center mt-4">
                                <ul class="flex items-center space-x-2">
                                    <?php if ($current_page > 1): ?>
                                        <li>
                                            <a href="?page=<?= $current_page - 1 ?>" class="bg-yellow-600 text-white px-3 py-2 rounded-md">Previous</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <li>
                                            <a href="?page=<?= $i ?>" class="bg-gray-800 text-white px-3 py-2 rounded-md"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($current_page < $total_pages): ?>
                                        <li>
                                            <a href="?page=<?= $current_page + 1 ?>" class="bg-yellow-600 text-white px-3 py-2 rounded-md">Next</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>




                    </div>
                    <div x-show="openTab === 2" class="p-6 bg-gray-50 rounded-lg shadow-md">
                        <form action="addProduct.php" method="POST" class="space-y-6" enctype="multipart/form-data">
                            <div class="space-y-2">
                                <label class="block text-lg font-semibold text-gray-800">Product Name:</label>
                                <input type="text" name="name" placeholder="ex: Gilas Pilipinas Limmited Edition" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-lg font-semibold text-gray-800">Description:</label>
                                <textarea name="description" placeholder="ex: Jersey Of Marc Pingris Jersey Number 15 " class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"></textarea>
                            </div>
                            <div class="mb-4">
                                <span class="block text-sm font-medium text-gray-700">Size:</span>
                                <div class="flex space-x-4 mt-2">
                                    <label class="flex items-center"><input type="radio" name="size" value="SM" class="mr-2" /> SM</label>
                                    <label class="flex items-center"><input type="radio" name="size" value="M" class="mr-2" /> M</label>
                                    <label class="flex items-center"><input type="radio" name="size" value="L" class="mr-2" /> L</label>
                                    <label class="flex items-center"><input type="radio" name="size" value="XL" class="mr-2" /> XL</label>
                                    <label class="flex items-center"><input type="radio" name="size" value="XXL" class="mr-2" /> XXL</label>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-lg font-semibold text-gray-800">Price:</label>
                                <input type="number" placeholder="ex: 499" name="price" step="0.01" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            </div>
                            <div class="space-y-2">
                                <label class="block text-lg font-semibold text-gray-800">Image Upload (JPEG/PNG):</label>
                                <input type="file" name="image" accept=".jpeg,.jpg,.png" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" />
                            </div>
                            <button type="submit" class="mt-6 w-full bg-gradient-to-r from-yellow-500 to-indigo-600 text-white py-2 rounded-md shadow-lg hover:from-yellow-600 hover:to-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                                Add Product
                            </button>
                        </form>
                    </div>





                </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

        <!-- AlpineJS -->
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
        <!-- Font Awesome -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/js/all.min.js" integrity="sha256-KzZiKy0DWYsnwMF+X1DvQngQ2/FxF7MF3Ff72XcpuPs=" crossorigin="anonymous"></script>
</body>

</html>