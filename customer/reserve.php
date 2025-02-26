<?php
session_start();
require_once '../conf/conf.php';
require_once '../controller/ReservationController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = 2; // Replace with actual user ID (e.g., from session)
    $table_id = $_POST['table_id'];
    $reservation_date = $_POST['reservation_date'] . ' ' . $_POST['reservation_time'];
    $guests = $_POST['guests'];
    $customer_email = $_SESSION['email'];

    $controller = new ReservationController($conn);
    $result = $controller->createReservation($user_id, $table_id, $reservation_date, $guests, $customer_email); 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Status - Sapori D'Italia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <style>
        .status-card {
            max-width: 600px;
            margin: 2rem auto;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 1rem;
        }
        .success-bg {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #c3e6cb;
        }
        .error-bg {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            border-color: #f5c6cb;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">
<?php require_once('../includes/header.php'); ?>

<main class="flex-grow-1 mt-5 pt-5">
    <div class="container py-5">
        <div class="status-card card <?php echo $result['status'] === 'success' ? 'success-bg' : 'error-bg'; ?>">
            <div class="card-body text-center p-4">
                <div class="mb-4">
                    <?php if($result['status'] === 'success'): ?>
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 3rem;"></i>
                    <?php else: ?>
                        <i class="bi bi-x-circle-fill text-danger" style="font-size: 3rem;"></i>
                    <?php endif; ?>
                </div>
                
                <h2 class="card-title mb-3">
                    <?php if($result['status'] === 'success'): ?>
                        Reservation Successful!
                    <?php else: ?>
                        Reservation Failed
                    <?php endif; ?>
                </h2>
                
                <div class="card-text mb-4">
                    <?= $result['message'] ?>
                </div>

                <div class="details mb-4">
                    <?php if($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
                        <div class="list-group">
                            <div class="list-group-item">
                                <i class="bi bi-calendar-event me-2"></i>
                                <?= date('F j, Y', strtotime($_POST['reservation_date'])) ?>
                            </div>
                            <div class="list-group-item">
                                <i class="bi bi-clock me-2"></i>
                                <?= date('g:i A', strtotime($_POST['reservation_time'])) ?>
                            </div>
                            <div class="list-group-item">
                                <i class="bi bi-people me-2"></i>
                                <?= $_POST['guests'] ?> Guests
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <a href="reservation.php" class="btn btn-primary">
                    <i class="bi bi-arrow-left me-2"></i>
                    Back to Reservations
                </a>
            </div>
        </div>
    </div>
</main>

<?php require_once('../includes/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>