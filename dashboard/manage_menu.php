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

<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-utensils mr-10"></i>
        Menu Management
      </h2>
      <div class="d-flex align-center">
        <span class="fs-14"><?= date('F j, Y') ?></span>
      </div>
    </div>

    <div class="wrapper d-grid gap-20 p-20" style="grid-template-columns: 1fr;">
      <!-- Alerts -->
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

      <!-- Dish Form -->
      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex mb-20">
          <div class="d-flex align-center gap-10">
            <h3 class="m-0 c-red fs-22">
              <?= $editDish ? '✏️ Edit Dish' : '🍴 Add New Dish' ?>
            </h3>
          </div>
          <i class="fa-solid fa-burger fa-2x c-red"></i>
        </div>

        <form method="post" enctype="multipart/form-data">
          <?php if ($editDish): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $editDish['id'] ?>">
            <input type="hidden" name="existing_image" value="<?= $editDish['image'] ?? '' ?>">
          <?php else: ?>
            <input type="hidden" name="action" value="add">
          <?php endif; ?>

          <div class="d-grid gap-25">
            <div class="row g-4">
              <!-- Dish Name -->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Dish Name</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="text" class="b-none bg-transparent p-10 w-full fs-14"
                      name="name"
                      value="<?= htmlspecialchars($editDish['name'] ?? '') ?>"
                      required>
                    <i class="fa-solid fa-tag p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <!-- Price -->
              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Price</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="number" step="0.01" class="b-none bg-transparent p-10 w-full fs-14"
                      name="price"
                      value="<?= $editDish['price'] ?? '' ?>"
                      required>
                    <i class="fa-solid fa-dollar-sign p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <!-- Description -->
              <div class="col-12">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Description</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <textarea class="b-none bg-transparent p-10 w-full fs-14"
                      name="description"
                      rows="1"><?= htmlspecialchars($editDish['description'] ?? '') ?></textarea>
                    <i class="fa-solid fa-align-left p-absolute c-grey"
                      style="right: 15px; top: 20px;"></i>
                  </div>
                </div>
              </div>

              <!-- Category -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Category</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <select class="b-none bg-transparent p-10 w-full fs-14 appearance-none"
                      name="category_id"
                      required>
                      <option value="">Choose Category</option>
                      <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= ($editDish['category_id'] ?? '') == $category['id'] ? 'selected' : '' ?>>
                          <?= htmlspecialchars($category['name']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>

                  </div>
                </div>
              </div>

              <!-- Availability -->
              <div class="col-md-4">
                <label class="form-check-label ms-5 c-grey fs-16 fw-bold "
                  for="availability">Available</label>
                <div class="form-check form-switch px-5 py-2">
                  <input class="form-check-input ms-3" type="checkbox"
                    id="availability" name="availability"
                    <?= ($editDish['availability'] ?? 0) ? 'checked' : '' ?>
                    style="transform: scale(1.5)">
                </div>
              </div>

              <!-- Image Upload -->
              <div class="col-md-4">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Dish Image</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="file"
                      class="d-none"
                      id="image"
                      name="image"
                      onchange="previewImage(event)">
                    <label for="image" class="d-block p-10 w-full c-pointer">
                      <span class="c-grey">Choose file...</span>
                    </label>
                    <?php if ($editDish && !empty($editDish['image'])): ?>
                      <div class="image-preview mt-10">
                        <img src="../uploads/<?= $editDish['image'] ?>"
                          class="rad-6 thumbnail"
                          width="100">
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="d-flex align-center gap-15 mt-10">
              <button type="submit" class="btn-shape bg-red c-white border-0 p-relative overflow-hidden">
                <span class="z-1 p-relative">
                  <?= $editDish ? '🔄 Update Dish' : '➕ Add Dish' ?>
                </span>
                <div class="hover-effect bg-red-alt-color p-absolute w-full h-full"></div>
              </button>
              <?php if ($editDish): ?>
                <a href="?" class="btn-shape bg-grey c-black border-0 hover-bg-dark-grey transition">
                  ❌ Cancel
                </a>
              <?php endif; ?>
            </div>
          </div>
        </form>
      </div>

      <!-- Menu Items Table -->
      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex mb-20">
          <h3 class="m-0 c-red">Menu Items</h3>
          <i class="fa-solid fa-list-ul fa-2x c-red"></i>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">ID</th>
                <th class="p-15">Name</th>
                <th class="p-15">Description</th>
                <th class="p-15">Price</th>
                <th class="p-15">Category</th>
                <th class="p-15">Status</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($dishes as $dish): ?>
                <tr class="border-bottom-eee">
                  <td class="p-15"><?= $dish['id'] ?></td>
                  <td class="p-15"><?= htmlspecialchars($dish['name']) ?></td>
                  <td class="p-15"><?= htmlspecialchars($dish['description']) ?></td>
                  <td class="p-15">$<?= number_format($dish['price'], 2) ?></td>
                  <td class="p-15"><?= $categoryMap[$dish['category_id']] ?? 'Uncategorized' ?></td>
                  <td class="p-15">
                    <span class="badge bg-<?= $dish['availability'] ? 'success' : 'danger' ?> c-white rad-6 p-5-10">
                      <?= $dish['availability'] ? 'Available' : 'Unavailable' ?>
                    </span>
                  </td>
                  <td class="p-15 between-flex">
                    <a href="?edit_id=<?= $dish['id'] ?>" class="btn-shape bg-orange c-white">
                      <i class="fa-solid fa-pencil fs-14"></i>
                    </a>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $dish['id'] ?>">
                      <button type="submit" class="btn-shape bg-red c-white border-0"
                        onclick="return confirm('Delete this dish?')">
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

  .toggle-switch {
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .toggle-checkbox:checked+.toggle-switch-label {
    background-color: var(--green-color);
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

<script>
  function previewImage(event) {
    const preview = document.querySelector('.image-preview');
    const file = event.target.files[0];
    const reader = new FileReader();

    reader.onload = function(e) {
      if (!preview) {
        const div = document.createElement('div');
        div.className = 'image-preview mt-10';
        div.innerHTML = `<img src="${e.target.result}" class="rad-6 thumbnail" width="100">`;
        event.target.parentNode.appendChild(div);
      } else {
        preview.querySelector('img').src = e.target.result;
      }
    }

    if (file) {
      reader.readAsDataURL(file);
    }
  }
</script>