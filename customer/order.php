<?php
// order.php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    header('Location: ../dashboard/index.php');
    exit;
}

// Include modules
require_once '../models/OrderModel.php';
require_once '../models/OrderItemModel.php';
require_once '../controller/CartModel.php';

// Initialize models
$orderModel = new OrderModel();
$orderItemModel = new OrderItemModel();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['dish_id'])) {
    $dish_id = $_POST['dish_id'];
    $dish_name = $_POST['dish_name'];
    $dish_price = $_POST['dish_price'];
    $quantity = $_POST['quantity'];

    // Add the item to the cart
    CartModel::addToCart($dish_id, $dish_name, $dish_price, $quantity);

    // Redirect back to the previous page
    $referer = $_SERVER['HTTP_REFERER'] ?? 'menu.php';
    header("Location: $referer");
    exit();
}




// Handle deleting items from the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_item'])) {
    $dish_id = $_POST['delete_item'];

    // Remove the item from the cart
    CartModel::removeFromCart($dish_id);

    // Check if the cart is empty after deletion
    $cart = CartModel::getCart();
    if (empty($cart)) {
        header('Location: ./menu.php'); // Redirect to menu if cart is empty
        exit();
    }

    // Redirect back to the order page if cart is not empty
    header("Location: order.php");
    exit();
}

// Check if the cart is empty (initial load or after other operations)
$cart = CartModel::getCart();
if (empty($cart)) {
    header('Location: ./menu.php'); // Redirect to menu if cart is empty
    exit();
}




// Calculate the total price
$total_price = 0;
foreach ($cart as $item) {
    $total_price += $item['dish_price'] * $item['quantity'];
}

// Handle form submission for placing the order
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['address'])) {
    // Get form data
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // Create the order
    $order_id = $orderModel->createOrder($_SESSION['user_id'], $total_price, $address, $payment_method);

    // Add order items
    $orderItemModel->addOrderItems($order_id, $cart);

    // Clear the cart
    CartModel::clearCart();

    // Redirect to a success page
    header('Location: ../customer/order-success.php');
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

        #visa-payment-section {
            display: none;
            /* Hidden by default */
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
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['dish_name']) ?></td>
                            <td><?= htmlspecialchars($item['quantity']) ?></td>
                            <td>$<?= number_format($item['dish_price'], 2) ?></td>
                            <td>$<?= number_format($item['dish_price'] * $item['quantity'], 2) ?></td>
                            <td>
                                <form action="" method="POST" style="display: inline;">
                                    <input type="hidden" name="delete_item" value="<?= $item['dish_id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-end p-3">
                <h4>Total: $<?= number_format($total_price, 2) ?></h4>
                <form action="" method="POST">
                    <!-- Address Input -->
                    <div class="mb-3">
                        <label for="address" class="form-label">Delivery Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                    </div>

                    <!-- Payment Method Selection -->
                    <div class="mb-3">
                        <label class="form-label">Payment Method</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="cash" value="cash" checked>
                                <label class="form-check-label" for="cash">Cash</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method" id="visa" value="visa">
                                <label class="form-check-label" for="visa">Visa</label>
                            </div>
                        </div>
                    </div>

                    <!-- Visa Payment Section (Hidden by Default) -->
                    <div id="visa-payment-section" class="mb-3">
                        <label for="card_number" class="form-label">Card Number</label>
                        <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                        <label for="expiry_date" class="form-label mt-2">Expiry Date</label>
                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                        <label for="cvv" class="form-label mt-2">CVV</label>
                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                    </div>

                    <!-- Place Order Button -->
                    <button type="submit" class="btn btn-success btn-lg">Place Order</button>
                </form>
            </div>
        </div>
    </div>
    <?php require_once('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript to Toggle Visa Payment Section -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const visaRadio = document.getElementById('visa');
            const cashRadio = document.getElementById('cash');
            const visaPaymentSection = document.getElementById('visa-payment-section');

            visaRadio.addEventListener('change', function() {
                if (this.checked) {
                    visaPaymentSection.style.display = 'block';
                }
            });

            cashRadio.addEventListener('change', function() {
                if (this.checked) {
                    visaPaymentSection.style.display = 'none';
                }
            });
        });
    </script>
</body>

</html>