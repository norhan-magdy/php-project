<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

// Include necessary files
require_once '../models/OrderModel.php';
require_once '../models/OrderItemModel.php';

// Initialize models
$orderModel = new OrderModel($conn);
$orderItemModel = new OrderItemModel($conn);

// Fetch the user's orders
$user_id = $_SESSION['user_id'];
$orders = $orderModel->getOrdersByUserId($user_id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .order-card {
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            padding: 1rem;
            background-color: #f9f9f9;
        }

        .order-card h5 {
            margin-bottom: 1rem;
        }

        .order-item {
            padding: 0.5rem;
            border-bottom: 1px solid #eee;
        }

        .order-item:last-child {
            border-bottom: none;
        }
    </style>
</head>

<body class="d-flex flex-column min-vh-100">
    <?php require_once('../includes/header.php'); ?>

    <main class="flex-grow-1">
        <div class="container py-5">
            <h1 class="text-center mb-4">Order History</h1>

            <?php if (empty($orders)): ?>
                <div class="alert alert-info">You have no orders yet.</div>
            <?php else: ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <h5>Order #<?= $order['id'] ?></h5>
                        <p><strong>Date:</strong> <?= date('F j, Y, g:i A', strtotime($order['created_at'])) ?></p>
                        <p><strong>Total Price:</strong> $<?= number_format($order['total_price'], 2) ?></p>
                        <p><strong>Address:</strong> <?= $order['address'] ?></p>
                        <p><strong>Payment Method:</strong> <?= $order['payment_method'] ?></p>

                        <h6>Items:</h6>
                        <div class="order-items">
                            <?php
                            $items = $orderItemModel->getItemsByOrderId($order['id']);
                            foreach ($items as $item): ?>
                                <div class="order-item">
                                    <?= $item['name'] ?> -
                                    $<?= number_format($item['price_at_order'], 2) ?> x
                                    <?= $item['quantity'] ?> =
                                    $<?= number_format($item['price_at_order'] * $item['quantity'], 2) ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <!-- Reorder Button -->
                        <form action="../controller/reorder.php" method="POST" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                            <button type="submit" class="btn btn-primary btn-sm">Reorder</button>
                        </form>
                    </div>

                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </main>

    <?php require_once('../includes/footer.php'); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>