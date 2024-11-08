<?php
session_start();
include 'db_connection.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to login if not logged in
    exit();
}

// Check if order ID is provided
if (!isset($_GET['id'])) {
    header('Location: userOrder.php'); // Redirect back if no order ID
    exit();
}

$order_id = $_GET['id'];
$delete_stmt = null; // Initialize the statement variable

// Check if delete request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Prepare and delete order
    $delete_query = "DELETE FROM orders WHERE id = ?";
    $delete_stmt = $db->prepare($delete_query);
    $delete_stmt->bind_param("i", $order_id);

    if ($delete_stmt->execute()) {
        header('Location: userOrder.php'); // Redirect back to orders page
        exit();
    } else {
        $error = "Failed to delete order.";
    }
}

// Close the connection
if ($delete_stmt) {
    $delete_stmt->close();
}
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Order</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Confirm Delete</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-700 p-2 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <p class="mb-4">Are you sure you want to delete this order?</p>

        <form method="POST">
            <button type="submit" class="w-full bg-red-600 text-white py-2 rounded hover:bg-red-500 transition">Delete Order</button>
        </form>
        <a href="userOrder.php" class="mt-4 inline-block text-center text-blue-600 hover:underline">Cancel</a>
    </div>
</body>
</html>
