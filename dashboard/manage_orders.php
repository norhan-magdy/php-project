<?php
session_start();
require_once '../models/OrderModel.php';
require_once '../models/OrderItemModel.php';
require_once '../models/UserModel.php';

$orderModel = new OrderModel();
$orderItemModel = new OrderItemModel();
$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'update_status') {
    $orderId = (int)$_POST['order_id'];
    $newStatus = $_POST['status'];

    if ($orderModel->updateOrderStatus($orderId, $newStatus)) {
      $_SESSION['success'] = 'Order status updated successfully!';
    } else {
      $_SESSION['error'] = 'Failed to update order status';
    }
    header('Location: ?');
    exit();
  }

  if ($action === 'delete') {
    $orderId = (int)$_POST['order_id'];
    if ($orderModel->deleteOrder($orderId)) {
      $_SESSION['success'] = 'Order deleted successfully!';
    } else {
      $_SESSION['error'] = 'Failed to delete order';
    }
    header('Location: ?');
    exit();
  }
}

$orders = $orderModel->getAllOrders();
$users = $userModel->getAllUsers();
$userMap = array_column($users, 'username', 'id');

require_once('../includes/header.php');
?>

<div class="container">
  <div class="row flex-nowrap">
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0">
      <?php require_once('./sidebar.php'); ?>
    </div>

    <div class="col py-5 mt-5">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-lg rounded">
        <div class="container-fluid d-flex align-items-center">
          <h3 class="text-white fw-bold mb-0">
            <i class="fa-solid fa-utensils me-2"></i> Orders Management
          </h3>
        </div>
      </nav>


      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <div class="card shadow special">
        <div class="card-header bg-white">
          <h5 class="mb-0">All Orders</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Total Price</th>
                  <th>Order Date</th>
                  <th>Status</th>
                  <th>Payment Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($orders as $order): ?>
                  <?php $items = $orderItemModel->getItemsByOrderId($order['id']); ?>
                  <tr>
                    <td><?= $order['id'] ?></td>
                    <td><?= $userMap[$order['user_id']] ?? 'Guest' ?></td>
                    <td>$<?= number_format($order['total_price'], 2) ?></td>
                    <td><?= date('M j, Y H:i', strtotime($order['order_date'])) ?></td>
                    <td>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                          <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                          <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                          <option value="ready" <?= $order['status'] === 'ready' ? 'selected' : '' ?>>Ready</option>
                          <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?>">
                        <?= ucfirst($order['payment_status']) ?>
                      </span>
                    </td>
                    <td>
                      <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                        data-bs-target="#orderDetailsModal<?= $order['id'] ?>">
                        <i class="fa-solid fa-eye"></i>
                      </button>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this order?')">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>

                  <div class="modal fade" id="orderDetailsModal<?= $order['id'] ?>" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title">Order #<?= $order['id'] ?> Details</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row">
                            <div class="col-md-6">
                              <h6>Customer Information</h6>
                              <p>
                                Name: <?= $userMap[$order['user_id']] ?? 'Guest' ?><br>
                                Order Date: <?= date('M j, Y H:i', strtotime($order['order_date'])) ?>
                              </p>
                            </div>
                            <div class="col-md-6">
                              <h6>Order Summary</h6>
                              <p>
                                Total: $<?= number_format($order['total_price'], 2) ?><br>
                                Status: <?= ucfirst($order['status']) ?><br>
                                Payment Status: <?= ucfirst($order['payment_status']) ?>
                              </p>
                            </div>
                          </div>

                          <h6>Order Items</h6>
                          <table class="table table-sm">
                            <thead>
                              <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php foreach ($items as $item): ?>
                                <tr>
                                  <td><?= htmlspecialchars($item['name']) ?></td>
                                  <td><?= $item['quantity'] ?></td>
                                  <td>$<?= number_format($item['price_at_order'], 2) ?></td>
                                  <td>$<?= number_format($item['quantity'] * $item['price_at_order'], 2) ?></td>
                                </tr>
                              <?php endforeach; ?>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>