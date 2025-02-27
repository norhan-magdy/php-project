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
<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-boxes-stacked mr-10"></i>
        Inventory Management
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
          <div class="d-flex align-center gap-10">
            <h3 class="m-0 c-red fs-22">
              <?= $editItem ? '✏️ Edit Inventory Item' : '📦 Add New Item' ?>
            </h3>
          </div>
          <i class="fa-solid fa-warehouse fa-2x c-red"></i>
        </div>

        <form method="post">
          <?php if ($editItem): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
          <?php else: ?>
            <input type="hidden" name="action" value="add">
          <?php endif; ?>

          <div class="d-grid gap-25">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Item Name</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="text" class="b-none bg-transparent p-10 w-full fs-14"
                      name="item_name"
                      value="<?= htmlspecialchars($editItem['item_name'] ?? '') ?>"
                      required>
                    <i class="fa-regular fa-keyboard p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Quantity</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="number" class="b-none bg-transparent p-10 w-full fs-14"
                      name="quantity"
                      value="<?= $editItem['quantity'] ?? 0 ?>"
                      required>
                    <i class="fa-solid fa-hashtag p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Reorder Level</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="number" class="b-none bg-transparent p-10 w-full fs-14"
                      name="reorder_level"
                      value="<?= $editItem['reorder_level'] ?? 0 ?>">
                    <i class="fa-solid fa-bell p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Supplier</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <select class="b-none bg-transparent p-10 w-full fs-14 appearance-none"
                      name="supplier_id"
                      required>
                      <option value="">Select Supplier</option>
                      <?php foreach ($suppliers as $supplier): ?>
                        <option value="<?= $supplier['id'] ?>" <?= ($editItem['supplier_id'] ?? '') == $supplier['id'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($supplier['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                    <i class="fa-solid fa-chevron-down p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%); pointer-events: none;"></i>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex align-center gap-15 mt-10">
              <button type="submit" class="btn-shape bg-red c-white border-0 p-relative overflow-hidden">
                <span class="z-1 p-relative">
                  <?= $editItem ? '🔄 Update Item' : '➕ Add Item' ?>
                </span>
                <div class="hover-effect bg-red-alt-color p-absolute w-full h-full"></div>
              </button>
              <?php if ($editItem): ?>
                <a href="?" class="btn-shape bg-grey c-black border-0 hover-bg-dark-grey transition">
                  ❌ Cancel
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>



      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex mb-20">
          <h3 class="m-0 c-red">Inventory Items</h3>
          <i class="fa-solid fa-clipboard-list fa-2x c-red"></i>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">ID</th>
                <th class="p-15">Item Name</th>
                <th class="p-15">Quantity</th>
                <th class="p-15">Reorder Level</th>
                <th class="p-15">Supplier</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($inventoryItems as $item): ?>
                <tr class="border-bottom-eee">
                  <td class="p-15"><?= $item['id'] ?></td>
                  <td class="p-15"><?= htmlspecialchars($item['item_name']) ?></td>
                  <td class="p-15"><?= $item['quantity'] ?></td>
                  <td class="p-15"><?= $item['reorder_level'] ?? 'N/A' ?></td>
                  <td class="p-15"><?= $supplierMap[$item['supplier_id']] ?? 'Unknown' ?></td>
                  <td class="p-15 between-flex">
                    <a href="?edit_id=<?= $item['id'] ?>" class="btn-shape bg-orange c-white">
                      <i class="fa-solid fa-pencil fs-14"></i>
                    </a>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $item['id'] ?>">
                      <button type="submit" class="btn-shape bg-red c-white border-0"
                        onclick="return confirm('Delete this item?')">
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

  .border-bottom-eee:not(:last-child) {
    border-bottom: 1px solid #eee;
  }

  .wrapper {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }

  .form-group {
    margin-bottom: 15px;
  }

  .input-field {
    transition: all 0.3s ease;
    border: 2px solid transparent;
  }

  .input-field:hover {
    border-color: var(--red-color);
  }

  .input-field:focus-within {
    border-color: var(--red-color);
    box-shadow: 0 0 8px rgb(0 117 255 / 20%);
  }

  .input-field input:focus,
  .input-field select:focus {
    box-shadow: none !important;
  }

  .hover-effect {
    left: -100%;
    transition: all 0.4s ease;
  }

  .btn-shape:hover .hover-effect {
    left: 0;
  }

  .appearance-none {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }

  .hover-bg-dark-grey:hover {
    background-color: #666 !important;
    color: white !important;
  }
</style>