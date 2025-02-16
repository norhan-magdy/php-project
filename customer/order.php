<?php
// order.php
session_start();

// Include modules
require_once '../models/order.php';
require_once '../models/OrderItem.php';
require_once '../models/CartModel.php';



// Initialize models
$orderModel = new Order($db);
$orderItemModel = new OrderItem($db);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

// Check if the cart is empty
$cart = CartModel::getCart();
if (empty($cart)) {
    header('Location: cart.php');
    exit();
}

// Calculate the total price
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['dish_price'] * $item['quantity'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Create the order
    $order_id = $orderModel->createOrder($_SESSION['user_id'], $total_price);

    // Add order items
    $orderItemModel->addOrderItems($order_id, $cart);

    // Clear the cart
    CartModel::clearCart();

    // Redirect to a success page
    header('Location: order_success.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Place Order</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .order-container {
            max-width: 800px;
            margin: 250px auto 220px;
            padding: 20px;
        }
        .order-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
  height: 60px;
  width: 60px;
}
.logo-cont {
  margin: 0;
  padding: 5px;
  height: 70px;
  width: 70px;
  background-color: white;
  border-radius: 50px;
}
/* Navbar Customization */
.navbar {
  background-color: #343a40 !important;
}

.navbar-brand,
.nav-link {
  color: white !important;
}

.nav-link:hover {
  color: #ffc107 !important;
  /* Hover Effect */
}

    </style>
</head>
<body>
    <!-- Header -->
    <?php require_once('../includes/header.php'); ?>

    <div class="order-container">
        <h2 class="mb-4">Place Your Order</h2>
        <div class="order-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['dish_name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['dish_price'], 2) ?></td>
                            <td>$<?= number_format($item['dish_price'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end p-3">
                <h4>Total: $<?= number_format($total_price, 2) ?></h4>
                <form action="order_success.php" method="POST">
                    <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                </form>
            </div>
        </div>
    </div>
    <?php require_once('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>