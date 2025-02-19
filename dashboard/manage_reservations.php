<?php
session_start();
require_once '../models/ReservationModel.php';
require_once '../models/UserModel.php';
require_once '../models/TableModel.php';

$reservationModel = new ReservationModel();
$userModel = new UserModel();
$tableModel = new TableModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $action = $_POST['action'] ?? '';

  if ($action === 'update_status') {
    $reservationId = (int)$_POST['id'];
    $newStatus = $_POST['status'];

    $success = $reservationModel->updateReservationStatus($reservationId, $newStatus);
    $message = $success ? 'Reservation status updated!' : 'Failed to update status.';

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  } elseif ($action === 'delete') {
    $reservationId = (int)$_POST['id'];
    $success = $reservationModel->deleteReservation($reservationId);
    $message = $success ? 'Reservation deleted!' : 'Failed to delete reservation.';

    $_SESSION[$success ? 'success' : 'error'] = $message;
    header('Location: ?');
    exit();
  }
}

// Get all reservations with additional information
$reservations = $reservationModel->getAllReservations();
$users = $userModel->getAllUsers();
$tables = $tableModel->getAllTables();

// Create helper arrays for quick lookups
$userMap = [];
foreach ($users as $user) {
  $userMap[$user['id']] = $user['username'];
}

$tableMap = [];
foreach ($tables as $table) {
  $tableMap[$table['id']] = 'Table ' . $table['table_number'] . ' (' . $table['capacity'] . ' seats)';
}

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
            <i class="fa-regular fa-calendar-check me-2"></i> Reservations
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
          <h5 class="mb-0">All Reservations</h5>
        </div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>User</th>
                  <th>Table</th>
                  <th>Date & Time</th>
                  <th>Guests</th>
                  <th>Status</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($reservations as $reservation): ?>
                  <tr>
                    <td><?= $reservation['id'] ?></td>
                    <td><?= $userMap[$reservation['user_id']] ?? 'Unknown' ?></td>
                    <td><?= $tableMap[$reservation['table_id']] ?? 'Unknown' ?></td>
                    <td><?= date('M j, Y H:i', strtotime($reservation['reservation_date'])) ?></td>
                    <td><?= $reservation['guests'] ?></td>
                    <td>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                        <select name="status" class="form-select form-select-sm"
                          onchange="this.form.submit()">
                          <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                          <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                        </select>
                      </form>
                    </td>
                    <td>
                      <a href="edit_reservation.php?id=<?= $reservation['id'] ?>" class="btn btn-sm btn-warning">
                        <i class="fa-solid fa-pencil"></i>
                      </a>
                      <form method="post" class="d-inline">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger"
                          onclick="return confirm('Are you sure you want to delete this reservation?')">
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