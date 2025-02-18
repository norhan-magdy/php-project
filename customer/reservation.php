<?php
include_once '../conf/conf.php';
session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: ' . ($_SESSION['role'] === 'staff' ? '../dashboard/index.php' : 'dashboardUser.php'));
    exit;
}

$sql = "SELECT table_number, capacity, location FROM tables";
$result = $conn->query($sql);


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css"></head>
<style>
      .order-container {
            max-width: 800px;
            margin: 250px auto 220px;
            padding: 20px;
        }
        .order-table {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .logo {
  height: 60px;
  width: 60px;
}
.logo-cont {
  margin: 0;
  padding: 5px;
  height: 70px;
  width: 70px;
  background-color: white;
  border-radius: 50px;
}
/* Navbar Customization */
.navbar {
  background-color: #343a40 !important;
}

.navbar-brand,
.nav-link {
  color: white !important;
}

.nav-link:hover {
  color: #ffc107 !important;
  /* Hover Effect */
}

body {
  padding-top: 70px;
}
</style>
<body>
<?php require_once('../includes/header.php'); ?>

<div class="container custom-container">
    <h2>Table Reservation</h2>
    
    <table class="table">
        <thead>
            <tr>
                <th>Table Number</th>
                <th>Capacity</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($table = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $table['table_number']; ?></td>
                        <td><?php echo $table['capacity']; ?></td>
                        <td><?php echo $table['location']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3">No tables available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h3>Reserve a Table</h3>
    <form method="POST" action="reserve.php">
        <div class="form-group">
            <label for="table_number">Table Number:</label>
            <input type="number" name="table_number" class="form-control" required min="1" max="10">
        </div>
        <div class="form-group">
            <label for="reservation_date">Select Date:</label>
            <input type="date" name="reservation_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="reservation_time">Select Time:</label>
            <input type="time" name="reservation_time" class="form-control"  >
        </div>
        <div class="form-group">
            <label for="guests">guests Number:</label>
            <input type="number" name="guests" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Confirm Reservation</button>
    </form>
</div>
<?php require_once('../includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src