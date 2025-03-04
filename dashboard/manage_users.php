<?php
session_start();
require_once '../models/UserModel.php';

$userModel = new UserModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'add' || $action === 'update') {
    $userData = [
      'username' => $_POST['username'] ?? '',
      'email' => $_POST['email'] ?? '',
      'phone' => $_POST['phone'] ?? '',
      'profile_picture' => ''
    ];

    if (!empty($_POST['password'])) {
      $userData['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
    }

    $image = $_FILES['profile_picture'] ?? null;
    if ($image && $image['error'] === UPLOAD_ERR_OK) {
      $uploadDir = '../uploads/profiles/';

      if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
      }

      $imageName = uniqid() . '_' . basename($image['name']);
      $targetPath = $uploadDir . $imageName;

      if (move_uploaded_file($image['tmp_name'], $targetPath)) {
        $userData['profile_picture'] = $imageName;
      } else {
        $_SESSION['error'] = 'Failed to upload profile picture';
        header('Location: ?');
        exit();
      }
    } elseif ($action === 'update') {
      $userData['profile_picture'] = $_POST['existing_profile_picture'] ?? '';
    }

    if ($action === 'add') {
      $success = $userModel->registerUser(
        $userData['username'],
        $userData['password'] ?? 'defaultPassword',
        $userData['email'],
        $_POST['role'] ?? 'customer'
      );
      $message = $success ? 'User added successfully!' : 'Failed to add user.';
    } else {
      $success = $userModel->updateUser(
        (int)$_POST['id'],
        $userData['username'],
        $userData['email'],
        $userData['phone'],
        $userData['profile_picture']
      );
      $message = $success ? 'User updated successfully!' : 'Failed to update user.';
    }

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  } elseif ($action === 'delete') {
    $userId = (int)$_POST['id'];
    $user = $userModel->getUserById($userId);

    if ($user) {
      if (!empty($user['profile_picture'])) {
        $imagePath = "../uploads/profiles/{$user['profile_picture']}";
        if (file_exists($imagePath)) unlink($imagePath);
      }

      $success = $userModel->deleteUser($userId);
      $message = $success ? 'User deleted successfully!' : 'Failed to delete user.';
    } else {
      $message = 'User not found.';
    }

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  }
}

$editUser = null;
if (isset($_GET['edit_id'])) {
  $editId = (int)$_GET['edit_id'];
  $editUser = $userModel->getUserById($editId);
  if (!$editUser) {
    $_SESSION['error'] = 'User not found.';
    header('Location: ?');
    exit();
  }
}

$users = $userModel->conn->query("SELECT * FROM users")->fetch_all(MYSQLI_ASSOC);

require_once('../includes/header.php');
?>
<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-users mr-10"></i>
        User Management
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
              <?= $editUser ? '✏️ Edit User' : '👤 Add New User' ?>
            </h3>
          </div>
          <i class="fa-solid fa-user-gear fa-2x c-red"></i>
        </div>

        <form method="post" enctype="multipart/form-data">
          <?php if ($editUser): ?>
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
            <input type="hidden" name="existing_profile_picture" value="<?= $editUser['profile_picture'] ?? '' ?>">
          <?php else: ?>
            <input type="hidden" name="action" value="add">
          <?php endif; ?>

          <div class="d-grid gap-25">
            <div class="row g-4">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Username</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="text" class="b-none bg-transparent p-10 w-full fs-14"
                      name="username"
                      value="<?= htmlspecialchars($editUser['username'] ?? '') ?>"
                      required>
                    <i class="fa-solid fa-user-tag p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Email</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="email" class="b-none bg-transparent p-10 w-full fs-14"
                      name="email"
                      value="<?= htmlspecialchars($editUser['email'] ?? '') ?>"
                      required>
                    <i class="fa-solid fa-envelope p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Password</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="password" class="b-none bg-transparent p-10 w-full fs-14"
                      name="password"
                      <?= $editUser ? '' : 'required' ?>>
                    <i class="fa-solid fa-lock p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                  <?php if ($editUser): ?>
                    <small class="text-muted fs-12 c-grey">Leave blank to keep current password</small>
                  <?php endif; ?>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Phone</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="tel" class="b-none bg-transparent p-10 w-full fs-14"
                      name="phone"
                      value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>">
                    <i class="fa-solid fa-phone p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Role</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <select class="b-none bg-transparent p-10 w-full fs-14 appearance-none"
                      name="role"
                      required>
                      <option value="customer" <?= ($editUser['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                      <option value="staff" <?= ($editUser['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
                    </select>
                    <i class="fa-solid fa-user-shield p-absolute c-grey"
                      style="right: 15px; top: 50%; transform: translateY(-50%);"></i>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label class="d-block mb-8 c-grey fs-16 fw-bold">Profile Picture</label>
                  <div class="input-field bg-eee rad-6 p-relative">
                    <input type="file"
                      class="d-none"
                      id="profile_picture"
                      name="profile_picture"
                      onchange="previewImage(event)">
                    <label for="profile_picture" class="d-block p-10 w-full c-pointer">
                      <span class="c-grey">Choose file...</span>
                    </label>
                    <?php if ($editUser && !empty($editUser['profile_picture'])): ?>
                      <div class="image-preview mt-10">
                        <img src="../uploads/profiles/<?= $editUser['profile_picture'] ?>"
                          class="rad-6 thumbnail"
                          width="80">
                      </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>

            <div class="d-flex align-center gap-15 mt-10">
              <button type="submit" class="btn-shape bg-red c-white border-0 p-relative overflow-hidden">
                <span class="z-1 p-relative">
                  <?= $editUser ? '🔄 Update User' : '➕ Add User' ?>
                </span>
                <div class="hover-effect bg-red-alt-color p-absolute w-full h-full"></div>
              </button>
              <?php if ($editUser): ?>
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
          <h3 class="m-0 c-red">User List</h3>
          <i class="fa-solid fa-users-line fa-2x c-red"></i>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">ID</th>
                <th class="p-15">Username</th>
                <th class="p-15">Email</th>
                <th class="p-15">Role</th>
                <th class="p-15">Phone</th>
                <th class="p-15">Profile</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($users as $user): ?>
                <tr class="border-bottom-eee">
                  <td class="p-15"><?= $user['id'] ?></td>
                  <td class="p-15"><?= htmlspecialchars($user['username']) ?></td>
                  <td class="p-15"><?= htmlspecialchars($user['email']) ?></td>
                  <td class="p-15">
                    <span class="badge bg-<?= $user['role'] === 'staff' ? 'primary' : 'secondary' ?> c-white rad-6 p-5-10">
                      <?= ucfirst($user['role']) ?>
                    </span>
                  </td>
                  <td class="p-15"><?= $user['phone'] ? htmlspecialchars($user['phone']) : 'N/A' ?></td>
                  <td class="p-15">
                    <?php if (!empty($user['profile_picture'])): ?>
                      <img src="../uploads/profiles/<?= $user['profile_picture'] ?>"
                        class="rad-6 thumbnail"
                        width="50">
                    <?php else: ?>
                      <span class="c-grey">No image</span>
                    <?php endif; ?>
                  </td>
                  <td class="p-15 between-flex">
                    <a href="?edit_id=<?= $user['id'] ?>" class="btn-shape bg-orange c-white">
                      <i class="fa-solid fa-pencil fs-14"></i>
                    </a>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $user['id'] ?>">
                      <button type="submit" class="btn-shape bg-red c-white border-0"
                        onclick="return confirm('Delete this user?')">
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
        div.innerHTML = `<img src="${e.target.result}" class="rad-6 thumbnail" width="80">`;
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
</body>

</html>