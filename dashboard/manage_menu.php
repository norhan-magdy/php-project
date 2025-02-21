<?php
session_start();
require_once '../models/CategoryModel.php';
require_once '../models/DishModel.php';
require_once '../models/SpecialOfferModel.php';
require_once '../controller/CartModel.php';

$categoryModel = new CategoryModel();
$dishModel = new DishModel();
$specialOfferModel = new SpecialOfferModel($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add' || $action === 'update') {
    $dishData = [
      'name' => $_POST['name'] ?? '',
      'description' => $_POST['description'] ?? '',
      'price' => (float)($_POST['price'] ?? 0),
      'category_id' => (int)($_POST['category_id'] ?? 0),
      'availability' => isset($_POST['availability']) ? 1 : 0,
      'image' => ''
    ];

    $image = $_FILES['image'] ?? null;
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
      $uploadDir = '../uploads/';

      if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
          $_SESSION['error'] = 'Failed to create upload directory';
          header('Location: ?');
          exit();
        }
      }

      if (!is_writable($uploadDir)) {
        $_SESSION['error'] = 'Upload directory is not writable';
        header('Location: ?');
        exit();
      }

      $imageName = uniqid() . '_' . basename($image['name']);
      $targetPath = $uploadDir . $imageName;

      if (move_uploaded_file($image['tmp_name'], $targetPath)) {
        $dishData['image'] = $imageName;
      } else {
        $_SESSION['error'] = 'Failed to upload image. Server Error: ' . error_get_last()['message'];
        header('Location: ?');
        exit();
      }
    } elseif ($action === 'update') {
      $dishData['image'] = $_POST['existing_image'] ?? '';
    }

    if ($action === 'add') {
      $success = $dishModel->addDish($dishData);
      $message = $success ? 'Dish added successfully!' : 'Failed to add dish.';
    } else {
      $dishData['id'] = (int)$_POST['id'];
      $success = $dishModel->updateDish($dishData);
      $message = $success ? 'Dish updated successfully!' : 'Failed to update dish.';
    }

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  } elseif ($action === 'delete') {
    $dishId = (int)$_POST['id'];
    $dish = $dishModel->getDishById($dishId);

    if ($dish) {
      if (!empty($dish['image'])) {
        $imagePath = "../uploads/{$dish['image']}";
        if (file_exists($imagePath)) unlink($imagePath);
      }

      $success = $dishModel->deleteDish($dishId);
      $message = $success ? 'Dish deleted successfully!' : 'Failed to delete dish.';
    } else {
      $message = 'Dish not found.';
    }

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  }
}

$editDish = null;
if (isset($_GET['edit_id'])) {
  $editId = (int)$_GET['edit_id'];
  $editDish = $dishModel->getDishById($editId);
  if (!$editDish) {
    $_SESSION['error'] = 'Dish not found.';
    header('Location: ?');
    exit();
  }
}

$dishes = $dishModel->getAllDishes();
$categories = $categoryModel->getAllCategories();

$categoryMap = [];
foreach ($categories as $category) {
  $categoryMap[$category['id']] = $category['name'];
}

$totalItems = count($dishes);
$averagePrice = $dishModel->getAveragePrice();
$availableItems = $dishModel->countAvailableItems();

require_once('../includes/header.php');
?>

<div class="container">
  <div class="row flex-nowrap">
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 ">
      <?php require_once('./sidebar.php'); ?>
    </div>

    <div class="col py-5 mt-5">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-lg rounded">
        <div class="container-fluid d-flex align-items-center">
          <h3 class="text-white fw-bold mb-0">
            <i class="fa-solid fa-book-open me-2"></i> Menu Management
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
          <h5 class="mb-0"><?= $editDish ? 'Edit Dish' : 'Add New Dish' ?></h5>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <?php if ($editDish): ?>
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $editDish['id'] ?>">
              <input type="hidden" name="existing_image" value="<?= $editDish['image'] ?? '' ?>">
            <?php else: ?>
              <input type="hidden" name="action" value="add">
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="name" class="form-label">Dish Name</label>
                <input type="text" class="form-control" id="name" name="name"
                  value="<?= htmlspecialchars($editDish['name'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price"
                  value="<?= $editDish['price'] ?? '' ?>" required>
              </div>

              <div class="col-12">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="2"><?= htmlspecialchars($editDish['description'] ?? '') ?></textarea>
              </div>

              <div class="col-md-4">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                  <option value="">Choose...</option>
                  <?php foreach ($categories as $category): ?>
                    <option value="<?= $category['id'] ?>"
                      <?= ($editDish['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($category['name']) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>

              <div class="col-md-4">
                <label class="form-label">Availability</label>
                <div class="form-check mt-2">
                  <input class="form-check-input" type="checkbox" id="availability" name="availability"
                    value="1" <?= ($editDish['availability'] ?? 0) ? 'checked' : '' ?>>
                  <label class="form-check-label" for="availability">
                    Available
                  </label>
                </div>
              </div>

              <div class="col-md-4">
                <label for="image" class="form-label">Dish Image</label>
                <input type="file" class="form-control" id="image" name="image">
                <?php if ($editDish && !empty($editDish['image'])): ?>
                  <div class="mt-2">
                    <img src="../uploads/<?= $editDish['image'] ?>" width="80" class="img-thumbnail">
                  </div>
                <?php endif; ?>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <?= $editDish ? 'Update Dish' : 'Add Dish' ?>
                </button>
                <?php if ($editDish): ?>
                  <a href="?" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>


      <div class="card shadow special">
        <div class="card-header bg-white">
          <h5 class="mb-0">Menu Items</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Description</th>
                  <th>Price</th>
                  <th>Category</th>
                  <th>Availability</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($dishes as $dish): ?>
                  <tr>
                    <td><?= $dish['id'] ?></td>
                    <td><?= htmlspecialchars($dish['name']) ?></td>
                    <td><?= htmlspecialchars($dish['description']) ?></td>
                    <td>$<?= number_format($dish['price'], 2) ?></td>

                    <td><?= $categoryMap[$dish['category_id']] ?? 'Uncategorized' ?></td>
                    <td>
                      <span class="badge bg-<?= $dish['availability'] ? 'success' : 'danger' ?>">
                        <?= $dish['availability'] ? 'Available' : 'Unavailable' ?>
                      </span>
                    </td>
                    <td>
                      <a href="?edit_id=<?= $dish['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $dish['id'] ?>">
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