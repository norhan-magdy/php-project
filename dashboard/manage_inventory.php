<?php
session_start();
require_once '../models/InventoryModel.php';
require_once '../models/SupplierModel.php';

$inventoryModel = new InventoryModel();
$supplierModel = new SupplierModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add' || $action === 'update') {
    $inventoryData = [
      'item_name' => $_POST['item_name'] ?? '',
      'quantity' => (int)($_POST['quantity'] ?? 0),
      'reorder_level' => (int)($_POST['reorder_level'] ?? 0),
      'supplier_id' => (int)($_POST['supplier_id'] ?? 0)
    ];

    if ($action === 'add') {
      $success = $inventoryModel->addInventoryItem($inventoryData);
      $message = $success ? 'Item added successfully!' : 'Failed to add item.';
    } else {
      $inventoryData['id'] = (int)$_POST['id'];
      $success = $inventoryModel->updateInventoryItem($inventoryData);
      $message = $success ? 'Item updated successfully!' : 'Failed to update item.';
    }

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  } elseif ($action === 'delete') {
    $itemId = (int)$_POST['id'];
    $success = $inventoryModel->deleteInventoryItem($itemId);
    $message = $success ? 'Item deleted successfully!' : 'Failed to delete item.';

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  }
}

$editItem = null;
if (isset($_GET['edit_id'])) {
  $editId = (int)$_GET['edit_id'];
  $editItem = $inventoryModel->getInventoryItemById($editId);
  if (!$editItem) {
    $_SESSION['error'] = 'Item not found.';
    header('Location: ?');
    exit();
  }
}

$inventoryItems = $inventoryModel->getAllInventoryItems();
$suppliers = $supplierModel->getAllSuppliers();
$supplierMap = array_column($suppliers, 'name', 'id');

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
            <i class="fa-solid fa-boxes-stacked me-2"></i> Inventory Management
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

      <div class="card shadow mb-4 special">
        <div class="card-header bg-white">
          <h5 class="mb-0"><?= $editItem ? 'Edit Inventory Item' : 'Add New Inventory Item' ?></h5>
        </div>
        <div class="card-body">
          <form method="post">
            <?php if ($editItem): ?>
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
            <?php else: ?>
              <input type="hidden" name="action" value="add">
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="item_name" class="form-label">Item Name</label>
                <input type="text" class="form-control" id="item_name" name="item_name"
                  value="<?= htmlspecialchars($editItem['item_name'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control" id="quantity" name="quantity"
                  value="<?= $editItem['quantity'] ?? 0 ?>" required>
              </div>

              <div class="col-md-6">
                <label for="reorder_level" class="form-label">Reorder Level</label>
                <input type="number" class="form-control" id="reorder_level" name="reorder_level"
                  value="<?= $editItem['reorder_level'] ?? 0 ?>">
              </div>

              <div class="col-md-6">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select class="form-select" id="supplier_id" name="supplier_id" required>
                  <option value="">Select Supplier</option>
                  <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id'] ?>"
                      <?= ($editItem['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($supplier['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <?= $editItem ? 'Update Item' : 'Add Item' ?>
                </button>
                <?php if ($editItem): ?>
                  <a href="?" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow special">
        <div class="card-header bg-white">
          <h5 class="mb-0">Inventory Items</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Item Name</th>
                  <th>Quantity</th>
                  <th>Reorder Level</th>
                  <th>Supplier</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($inventoryItems as $item): ?>
                  <tr>
                    <td><?= $item['id'] ?></td>
                    <td><?= htmlspecialchars($item['item_name']) ?></td>
                    <td><?= $item['quantity'] ?></td>
                    <td><?= $item['reorder_level'] ?? 'N/A' ?></td>
                    <td><?= $supplierMap[$item['supplier_id']] ?? 'Unknown' ?></td>
                    <td>
                      <a href="?edit_id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $item['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this item?')">
                          <i class="fa-solid fa-trash"></i>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>