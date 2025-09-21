<?php
session_start();

require_once '../models/OrderModel.php';
$orderModel = new OrderModel();

if (isset($_SESSION['pending_order_id'])) {
    $order_id = $_SESSION['pending_order_id'];
    $orderModel->updatePaymentStatus($order_id, 'cancelled');
    unset($_SESSION['pending_order_id']);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Payment Cancelled</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .hei-100{
      min-height: calc(100vh - 204px);
    }
  </style>
</head>
<body>
  <?php require_once('../includes/header.php'); ?>

  <div class="container d-flex justify-content-center align-items-center hei-100">
    <div class="alert alert-warning text-center p-5">
      <h2>Payment Cancelled</h2>
      <p>Your PayPal payment was cancelled. You can try again or choose another payment method.</p>
      <a href="../customer/profile.php" class="btn btn-primary">Back to your order history</a>
      <a href="../customer/menu.php" class="btn btn-primary">Back to menu page</a>
    </div>
  </div>
  
  <?php require_once('../includes/footer.php'); ?>
</body>
</html>
