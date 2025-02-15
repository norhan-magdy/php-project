<?php
include_once '../conf/conf.php';

$sql = "SELECT table_number, capacity, location FROM tables";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-5">
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
            <input type="number" name="table_number" class="form-control" required>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src