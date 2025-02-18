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
<div class="container">
  <div class="row flex-nowrap">
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 ">
      <?php require_once('./sidebar.php'); ?>
    </div>

    <div class="col py-5 mt-5">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-lg rounded">
        <div class="container-fluid d-flex align-items-center">
          <h3 class="text-white fw-bold mb-0">
            <i class="fa-solid fa-users me-2"></i> User Management
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
          <h5 class="mb-0"><?= $editUser ? 'Edit User' : 'Add New User' ?></h5>
        </div>
        <div class="card-body">
          <form method="post" enctype="multipart/form-data">
            <?php if ($editUser): ?>
              <input type="hidden" name="action" value="update">
              <input type="hidden" name="id" value="<?= $editUser['id'] ?>">
              <input type="hidden" name="existing_profile_picture" value="<?= $editUser['profile_picture'] ?? '' ?>">
            <?php else: ?>
              <input type="hidden" name="action" value="add">
            <?php endif; ?>

            <div class="row g-3">
              <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                  value="<?= htmlspecialchars($editUser['username'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                  value="<?= htmlspecialchars($editUser['email'] ?? '') ?>" required>
              </div>

              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"
                  <?= $editUser ? '' : 'required' ?>>
                <?php if ($editUser): ?>
                  <small class="text-muted">Leave blank to keep current password</small>
                <?php endif; ?>
              </div>

              <div class="col-md-6">
                <label for="phone" class="form-label">Phone</label>
                <input type="tel" class="form-control" id="phone" name="phone"
                  value="<?= htmlspecialchars($editUser['phone'] ?? '') ?>">
              </div>

              <div class="col-md-6">
                <label for="role" class="form-label">Role</label>
                <select class="form-select" id="role" name="role" required>
                  <option value="customer" <?= ($editUser['role'] ?? '') === 'customer' ? 'selected' : '' ?>>Customer</option>
                  <option value="staff" <?= ($editUser['role'] ?? '') === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>
              </div>

              <div class="col-md-6">
                <label for="profile_picture" class="form-label">Profile Picture</label>
                <input type="file" class="form-control" id="profile_picture" name="profile_picture">
                <?php if ($editUser && !empty($editUser['profile_picture'])): ?>
                  <div class="mt-2">
                    <img src="../uploads/profiles/<?= $editUser['profile_picture'] ?>" width="80" class="img-thumbnail">
                  </div>
                <?php endif; ?>
              </div>

              <div class="col-12">
                <button type="submit" class="btn btn-primary">
                  <?= $editUser ? 'Update User' : 'Add User' ?>
                </button>
                <?php if ($editUser): ?>
                  <a href="?" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="card shadow special">
        <div class="card-header bg-white">
          <h5 class="mb-0">User List</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Username</th>
                  <th>Email</th>
                  <th>Role</th>
                  <th>Phone</th>
                  <th>Profile</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($users as $user): ?>
                  <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td>
                      <span class="badge bg-<?= $user['role'] === 'staff' ? 'primary' : 'secondary' ?>">
                        <?= ucfirst($user['role']) ?>
                      </span>
                    </td>
                    <td><?= isset($user['phone']) && $user['phone'] !== null ? htmlspecialchars($user['phone'], ENT_QUOTES, 'UTF-8') : 'N/A' ?></td>
                    <td>
                      <?php if (!empty($user['profile_picture'])): ?>
                        <img src="../uploads/profiles/<?= $user['profile_picture'] ?>" width="50" class="img-thumbnail">
                      <?php else: ?>
                        <span class="text-muted">No image</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <a href="?edit_id=<?= $user['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $user['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this user?')">
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