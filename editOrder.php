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

// Fetch current order details
$query = "SELECT status FROM orders WHERE id = ?";
$stmt = $db->prepare($query);
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];

    // Update order status
    $update_query = "UPDATE orders SET status = ? WHERE id = ?";
    $update_stmt = $db->prepare($update_query);
    $update_stmt->bind_param("si", $new_status, $order_id);

    if ($update_stmt->execute()) {
        header('Location: userOrder.php'); // Redirect back to orders page
        exit();
    } else {
        $error = "Failed to update order status.";
    }
}

$stmt->close();
$db->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order Status</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-4">Edit Order Status</h2>
        
        <?php if (isset($error)): ?>
            <div class="bg-red-200 text-red-700 p-2 rounded mb-4">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-4">
                <label for="status" class="block text-gray-700">Select Status</label>
                <select name="status" id="status" class="border border-gray-300 rounded w-full p-2">
                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Delivery" <?php echo $order['status'] === 'Delivery' ? 'selected' : ''; ?>>Delivery</option>
                    <option value="Finished" <?php echo $order['status'] === 'Finished' ? 'selected' : ''; ?>>Finished</option>
                </select>
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-500 transition">Update Status</button>
        </form>
        <a href="userOrder.php" class="mt-4 inline-block text-center text-blue-600 hover:underline">Cancel</a>
    </div>
</body>
</html>
