<?php
session_start();

// Include models
require_once '../models/OrderModel.php';


// Initialize models
$orderModel = new OrderModel();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

// Get the order ID from the query parameter
if (!isset($_GET['order_id'])) {
    die("Order ID is missing.");
}
$order_id = $_GET['order_id'];

// Fetch order details
$orderDetails = $orderModel->getOrderDetails($order_id);

// Verify the order belongs to the logged-in user
if ($orderDetails['user_id'] != $_SESSION['user_id']) {
    die("You are not authorized to view this order.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Success</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    
</head>

<body>
    
    <!-- Header -->
    <?php require_once('../includes/header.php'); ?>

    <div class="container mt-5 pt-5">
        <div class="alert alert-success  text-center mt-5 pt-5">
            <h2>Order Placed Successfully!</h2>
            <p>Thank you for your order. Here are the details:</p>
            <p><strong>Order ID:</strong> <?= htmlspecialchars($order_id) ?></p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    <?= $orderDetails['status'] === 'pending' ? 'bg-warning' : '' ?>
                    <?= $orderDetails['status'] === 'preparing' ? 'bg-info' : '' ?>
                    <?= $orderDetails['status'] === 'ready' ? 'bg-primary' : '' ?>
                    <?= $orderDetails['status'] === 'delivered' ? 'bg-success' : '' ?>">
                    <?= ucfirst($orderDetails['status']) ?>
                </span>
            </p>
            <p><strong>Total Price:</strong> $<?= number_format($orderDetails['total_price'], 2) ?></p>
            <p><strong>Delivery Address:</strong> <?= htmlspecialchars($orderDetails['address']) ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($orderDetails['phone']) ?></p>
            <a href="../customer/menu.php" class="btn btn-primary">Back to Menu</a>
        </div>
    </div>

    <div class="fixed-bottom">
        <!-- Footer -->
    <?php require_once('../includes/footer.php'); ?>

    </div>
    <!-- Footer -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>