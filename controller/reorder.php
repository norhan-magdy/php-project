<?php
session_start();

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

// Include necessary files
require_once '../models/OrderItemModel.php';
require_once '../controller/CartModel.php';

// Initialize models
$orderItemModel = new OrderItemModel($conn);
$cartModel = new CartModel();

// Get the order ID from the form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Fetch the items from the order
    $items = $orderItemModel->getItemsByOrderId($order_id);

    // Add each item to the cart
    foreach ($items as $item) {
        $cartModel->addToCart(
            $item['item_id'], // dish_id
            $item['name'],     // dish_name
            $item['price_at_order'], // dish_price
            $item['quantity']  // quantity
        );
    }

    // Redirect to the cart page
    $_SESSION['reorder_success'] = "Items from Order #$order_id have been added to your cart.";
    header('Location: ../customer/order.php');
    exit();
} else {
    // If no order ID is provided, redirect to the order history page
    header('Location: ../customer/order_history.php');
    exit();
}
?>