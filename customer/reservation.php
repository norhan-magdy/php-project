<?php
session_start();
require_once '../controller/ReservationController.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../authentication/login.php');
    exit();
}

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    header('Location: ../dashboard/index.php');
    exit;
}

$controller = new ReservationController($someArgument);
$tables = $controller->getAllTables();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Form - Sapori D'Italia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .reservation-form {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .btn-primary {
            width: 100%;
            padding: 0.75rem;
            font-size: 1.1rem;
        }
        .table-option {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            background: #f8f9fa;
        }
        .table-option:hover {
            background: #e9ecef;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
<?php require_once('../includes/header.php'); ?>

<main class="flex-grow-1 mt-5 pt-5">
    <div class="container py-5">
        <div class="reservation-form">
            <h1 class="text-center mb-4">Make a Reservation</h1>
            <form method="POST" action="reserve.php">
                <!-- Number of Guests -->
                <div class="form-group">
                    <label for="guests">Number of Guests</label>
                    <input type="number" name="guests" class="form-control" required min="1" max="60" placeholder="Enter number of guests">
                </div>

                <!-- Table Selection -->
                <div class="form-group">
                    <label for="table_id">Select a Table</label>
                    <select name="table_id" class="form-control" required>
                        <?php foreach ($tables as $table): ?>
                            <option value="<?= $table['id'] ?>">
                                Table <?= $table['table_number'] ?> | Capacity: <?= $table['capacity'] ?> | Location: <?= $table['location'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Date and Time -->
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label for="reservation_date">Select Date</label>
                        <input type="date" name="reservation_date" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label for="reservation_time">Select Time</label>
                        <input type="time" name="reservation_time" class="form-control" required>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary mt-4">
                    <i class="bi bi-calendar-check me-2"></i>
                    Confirm Reservation
                </button>
            </form>
        </div>
    </div>
</main>

<?php require_once('../includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>