<?php
session_start();
require_once '../models/SpecialOfferModel.php';

$offerModel = new SpecialOfferModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add') {
    $name         = $_POST['name']         ?? '';
    $description  = $_POST['description']  ?? '';
    $discount     = (float)($_POST['discount'] ?? 0);
    $expiryDate   = $_POST['expiry_date']  ?? '';
    $applicableTo = $_POST['applicable_to'] ?? 'all';

    $success = $offerModel->addSpecialOffer($name, $description, $discount, $expiryDate, $applicableTo);
    $_SESSION[$success ? 'success' : 'error'] = $success
      ? 'Special offer added successfully!'
      : 'Failed to add special offer.';
    header('Location: ?');
    exit();
  }

  if ($action === 'update') {
    $id           = (int)($_POST['id'] ?? 0);
    $name         = $_POST['name']         ?? '';
    $description  = $_POST['description']  ?? '';
    $discount     = (float)($_POST['discount'] ?? 0);
    $expiryDate   = $_POST['expiry_date']  ?? '';
    $applicableTo = $_POST['applicable_to'] ?? 'all';

    $success = $offerModel->updateSpecialOffer($id, $name, $description, $discount, $expiryDate, $applicableTo);
    $_SESSION[$success ? 'success' : 'error'] = $success
      ? 'Special offer updated successfully!'
      : 'Failed to update special offer.';
    header('Location: ?');
    exit();
  }

  if ($action === 'delete') {
    $id = (int)($_POST['id'] ?? 0);
    $success = $offerModel->deleteSpecialOffer($id);
    $_SESSION[$success ? 'success' : 'error'] = $success
      ? 'Special offer deleted successfully!'
      : 'Failed to delete special offer.';
    header('Location: ?');
    exit();
  }
}

$offers = $offerModel->getAllSpecialOffers();

require_once('../includes/header.php');
?>
<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-star mr-10"></i> Special Offers
      </h2>
      <span class="fs-14"><?= date('F j, Y') ?></span>
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
          <h3 class="m-0 c-red">🎉 All Special Offers</h3>
          <button type="button" class="btn-shape bg-blue c-white border-0"
            data-bs-toggle="modal" data-bs-target="#addOfferModal">
            <i class="fa-solid fa-plus"></i> Add Offer
          </button>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">ID</th>
                <th class="p-15">Name</th>
                <th class="p-15">Description</th>
                <th class="p-15">Discount</th>
                <th class="p-15">Expiry Date</th>
                <th class="p-15">Applicable To</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($offers)): ?>
                <?php foreach ($offers as $offer): ?>
                  <tr class="border-bottom-eee">
                    <td class="p-15"><?= $offer['id'] ?></td>
                    <td class="p-15"><?= htmlspecialchars($offer['name']) ?></td>
                    <td class="p-15"><?= htmlspecialchars($offer['description']) ?></td>
                    <td class="p-15"><?= htmlspecialchars($offer['discount']) ?>%</td>
                    <td class="p-15"><?= date('M j, Y', strtotime($offer['expiry_date'])) ?></td>
                    <td class="p-15"><?= htmlspecialchars($offer['applicable_to']) ?></td>
                    <td class="p-15 between-flex">
                      <button type="button" class="btn-shape bg-orange c-white border-0"
                        data-bs-toggle="modal" data-bs-target="#editOfferModal<?= $offer['id'] ?>">
                        <i class="fa-solid fa-pencil"></i>
                      </button>

                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $offer['id'] ?>">
                        <button type="submit" class="btn-shape bg-red c-white border-0"
                          onclick="return confirm('Delete this offer?')">
                          <i class="fa-solid fa-trash"></i>
                        </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" class="p-15">No active special offers found.</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<!--
  MOVE THE MODALS OUTSIDE THE .dashboard-card / .wrapper CONTAINER
  so they are not affected by the transform on hover.
-->
<?php if (!empty($offers)): ?>
  <?php foreach ($offers as $offer): ?>
    <div class="modal fade" id="editOfferModal<?= $offer['id'] ?>" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <div class="modal-content rad-10">
          <div class="modal-header bg-red c-white p-15">
            <h5 class="m-0">Edit Offer #<?= $offer['id'] ?></h5>
            <button type="button" class="btn-close c-white" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body p-20">
            <form method="post">
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $offer['id'] ?>">

              <div class="mb-3">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control"
                  value="<?= htmlspecialchars($offer['name']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" required><?= htmlspecialchars($offer['description']) ?></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label">Discount (%)</label>
                <input type="number" name="discount" class="form-control" step="0.01"
                  value="<?= htmlspecialchars($offer['discount']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Expiry Date</label>
                <input type="date" name="expiry_date" class="form-control"
                  value="<?= htmlspecialchars($offer['expiry_date']) ?>" required>
              </div>

              <div class="mb-3">
                <label class="form-label">Applicable To</label>
                <select name="applicable_to" class="form-select" required>
                  <option value="all" <?= $offer['applicable_to'] === 'all' ? 'selected' : '' ?>>All Items</option>
                  <option value="specific" <?= $offer['applicable_to'] === 'specific' ? 'selected' : '' ?>>Specific Items</option>
                </select>
              </div>

              <button type="submit" class="btn-shape bg-blue c-white border-0">Save Changes</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<div class="modal fade" id="addOfferModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content rad-10">
      <div class="modal-header bg-red c-white p-15">
        <h5 class="m-0">Add New Offer</h5>
        <button type="button" class="btn-close c-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-20">
        <form method="post">
          <input type="hidden" name="action" value="add">

          <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required></textarea>
          </div>

          <div class="mb-3">
            <label class="form-label">Discount (%)</label>
            <input type="number" name="discount" class="form-control" step="0.01" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Expiry Date</label>
            <input type="date" name="expiry_date" class="form-control" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Applicable To</label>
            <select name="applicable_to" class="form-select" required>
              <option value="all">All Items</option>
              <option value="specific">Specific Items</option>
            </select>
          </div>

          <button type="submit" class="btn-shape bg-blue c-white border-0">Add Offer</button>
        </form>
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

  .thumbnail {
    border: 2px solid #eee;
    padding: 3px;
  }

  .badge {
    display: inline-block;
    min-width: 80px;
    text-align: center;
  }

  .hover-effect {
    left: -100%;
    transition: all 0.4s ease;
  }

  .btn-shape:hover .hover-effect {
    left: 0;
  }
</style>