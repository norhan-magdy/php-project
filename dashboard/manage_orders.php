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
<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-utensils mr-10"></i>
        Orders Management
      </h2>
      <div class="d-flex align-center">
        <span class="fs-14"><?= date('F j, Y') ?></span>
      </div>
    </div>

    <div class="wrapper d-grid gap-20 p-20" style="grid-template-columns: 1fr;">
      <?php if (isset($_SESSION['success'])): ?>
        <div class="alert bg-green c-white p-10 rad-6 fs-14">
          <?= $_SESSION['success'] ?>
        </div>
        <?php unset($_SESSION['success']); ?>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
        <div class="alert bg-red c-white p-10 rad-6 fs-14">
          <?= $_SESSION['error'] ?>
        </div>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex mb-20">
          <h3 class="m-0 c-red">All Orders</h3>
          <i class="fa-solid fa-receipt fa-2x c-red"></i>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">Order ID</th>
                <th class="p-15">Customer</th>
                <th class="p-15">Total Price</th>
                <th class="p-15">Order Date</th>
                <th class="p-15">Status</th>
                <th class="p-15">Payment</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <tr class="border-bottom-eee">
                  <td class="p-15"><?= $order['id'] ?></td>
                  <td class="p-15"><?= $userMap[$order['user_id']] ?? 'Guest' ?></td>
                  <td class="p-15">$<?= number_format($order['total_price'], 2) ?></td>
                  <td class="p-15"><?= date('M j, Y H:i', strtotime($order['order_date'])) ?></td>
                  <td class="p-15">
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="update_status">
                      <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                      <select name="status" class="input-field bg-eee rad-6 p-2 fs-14" onchange="this.form.submit()">
                        <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="preparing" <?= $order['status'] === 'preparing' ? 'selected' : '' ?>>Preparing</option>
                        <option value="ready" <?= $order['status'] === 'ready' ? 'selected' : '' ?>>Ready</option>
                        <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                      </select>
                    </form>
                  </td>
                  <td class="p-15">
                    <span class="badge bg-<?= $order['payment_status'] === 'paid' ? 'success' : 'warning' ?> fs-12">
                      <?= ucfirst($order['payment_status']) ?>
                    </span>
                  </td>
                  <td class="p-15 between-flex gap-10">
                    <button type="button" class="btn-shape bg-blue c-white border-0"
                      data-bs-toggle="modal" data-bs-target="#orderDetailsModal<?= $order['id'] ?>">
                      <i class="fa-solid fa-eye fs-14"></i>
                    </button>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                      <button type="submit" class="btn-shape bg-red c-white border-0"
                        onclick="return confirm('Delete this order?')">
                        <i class="fa-solid fa-trash fs-14"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php foreach ($orders as $order): ?>
  <?php $items = $orderItemModel->getItemsByOrderId($order['id']); ?>
  <div class="modal fade" id="orderDetailsModal<?= $order['id'] ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content rad-10 overflow-hidden">
        <div class="modal-header bg-red c-white p-15">
          <h5 class="m-0">Order #<?= $order['id'] ?> Details</h5>
          <button type="button" class="btn-close c-white border-0" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body p-20">
          <div class="d-grid gap-20 mb-20">
            <div class="bg-eee rad-6 p-15">
              <h6 class="c-red mt-0 mb-10">Customer Information</h6>
              <div class="fs-14">
                Name: <?= $userMap[$order['user_id']] ?? 'Guest' ?><br>
                Order Date: <?= date('M j, Y H:i', strtotime($order['order_date'])) ?>
              </div>
            </div>
            <div class="bg-eee rad-6 p-15">
              <h6 class="c-red mt-0 mb-10">Order Summary</h6>
              <div class="fs-14">
                Total: $<?= number_format($order['total_price'], 2) ?><br>
                Status: <?= ucfirst($order['status']) ?><br>
                Payment: <?= ucfirst($order['payment_status']) ?>
              </div>
            </div>
          </div>
          <h6 class="c-red mb-15">Order Items</h6>
          <div class="table-responsive">
            <table class="w-full bg-eee rad-6">
              <thead>
                <tr class="fs-14">
                  <th class="p-10">Item</th>
                  <th class="p-10">Qty</th>
                  <th class="p-10">Price</th>
                  <th class="p-10">Total</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $item): ?>
                  <tr class="border-bottom-eee">
                    <td class="p-10"><?= htmlspecialchars($item['name']) ?></td>
                    <td class="p-10"><?= $item['quantity'] ?></td>
                    <td class="p-10">$<?= number_format($item['price_at_order'], 2) ?></td>
                    <td class="p-10">$<?= number_format($item['quantity'] * $item['price_at_order'], 2) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php endforeach; ?>

<style>
  .border-top-red {
    border-top: 4px solid var(--red-color);
  }

  .dashboard-card {
    transition: transform 0.3s;
    box-shadow: 0 0 10px #00000010;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
  }

  .input-field {
    border: 2px solid transparent;
    transition: all 0.3s ease;
  }

  .input-field:focus-within {
    border-color: var(--red-color);
    box-shadow: 0 0 8px rgb(244 67 54 / 20%);
  }

  .badge {
    padding: 4px 8px;
    border-radius: 4px;
  }

  .btn-close {
    filter: invert(1);
  }

  @media (max-width: 767px) {

    table th,
    table td {
      padding: 8px;
      font-size: 13px;
    }

    .input-field {
      width: 100% !important;
    }
  }
</style>