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

$reservations = $reservationModel->getAllReservations();
$users = $userModel->getAllUsers();
$tables = $tableModel->getAllTables();

$userMap = array_column($users, 'username', 'id');
$tableMap = array_column($tables, null, 'id');

require_once('../includes/header.php');
?>

<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0"><i class="fa-regular fa-calendar-check mr-10"></i> Reservations</h2>
      <span class="fs-14"> <?= date('F j, Y') ?> </span>
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
          <h3 class="m-0 c-red">📅 All Reservations</h3>
          <i class="fa-solid fa-list fa-2x c-red"></i>
        </div>

        <div class="table-responsive">
          <table class="w-full">
            <thead>
              <tr class="bg-eee fs-14">
                <th class="p-15">ID</th>
                <th class="p-15">User</th>
                <th class="p-15">Table</th>
                <th class="p-15">Date & Time</th>
                <th class="p-15">Guests</th>
                <th class="p-15">Status</th>
                <th class="p-15">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($reservations as $reservation): ?>
                <tr class="border-bottom-eee">
                  <td class="p-15"> <?= $reservation['id'] ?> </td>
                  <td class="p-15"> <?= $userMap[$reservation['user_id']] ?? 'Unknown' ?> </td>
                  <td class="p-15"> <?= $tableMap[$reservation['table_id']]['table_number'] ?? 'Unknown' ?> </td>
                  <td class="p-15"> <?= date('M j, Y H:i', strtotime($reservation['reservation_date'])) ?> </td>
                  <td class="p-15"> <?= $reservation['guests'] ?> </td>
                  <td class="p-15">
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="update_status">
                      <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                      <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                        <option value="confirmed" <?= $reservation['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="cancelled" <?= $reservation['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                      </select>
                    </form>
                  </td>
                  <td class="p-15 between-flex">
                    <a href="edit_reservation.php?id=<?= $reservation['id'] ?>" class="btn-shape bg-orange c-white">
                      <i class="fa-solid fa-pencil fs-14"></i>
                    </a>
                    <form method="post" class="d-inline">
                      <input type="hidden" name="action" value="delete">
                      <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
                      <button type="submit" class="btn-shape bg-red c-white border-0" onclick="return confirm('Delete this reservation?')">
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

  .btn-shape:hover .hover-effect {
    left: 0;
  }

  .appearance-none {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }
</style>