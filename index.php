<?php
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    header('Location: ../dashboard/index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sapori D'Italia</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="./assets/CSS/style.css">

    <style>
        /* Custom Red Accent Color */
        :root {
            --accent-color: #D6232A;
        }

        /* Hero Section Styling */
        .hero-section {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            overflow: hidden;
        }

        /* Background Image with Blur and Dimming */
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('../assets/img/hero.jpg') no-repeat center center/cover;
            filter: blur(5px) brightness(40%); /* Blur and dim the background */
            z-index: -1; /* Place it behind the content */
        }

        /* Navbar Customization */
        .navbar {
            background-color: #FFF !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand,
        .nav-link {
            color: #000 !important;
        }

        .nav-link:hover {
            color: var(--accent-color) !important;
        }

        /* Buttons */
        .btn-warning {
            background-color: var(--accent-color) !important;
            border-color: var(--accent-color) !important;
            color: #FFF !important;
        }

        .btn-warning:hover {
            background-color: #B51D22;
            border-color: #B51D22;
        }

        .btn-outline-primary {
            border-color: var(--accent-color) !important;
            color: var(--accent-color) !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--accent-color) !important;
            color: #FFF !important;
        }

        /* Card Hover Effect */
        .card {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s ease-in-out;
        }

        .card i {
            color: var(--accent-color) !important;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php require_once('./includes/header.php'); ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="text-center p-5">
            <h1 class="display-3 fw-bold">Sapori D'Italia</h1>
            <img src="../assets/img/Logo.png" alt="logo" class="img-fluid">
            <p class="lead">Delicious food, great ambiance, and exceptional service.</p>
            <a href="/customer/menu.php" class="btn btn-warning btn-lg">Explore Menu</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fas fa-pizza-slice fa-3x mb-3"></i>
                    <div class="card-body">
                        <h3 class="card-title">Our Menu</h3>
                        <p class="card-text">Discover our wide range of dishes, from appetizers to desserts.</p>
                        <a href="../customer/menu.php" class="btn btn-outline-primary">View Menu</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fa-solid fa-utensils fa-3x mb-3"></i>
                    <div class="card-body">
                        <h3 class="card-title">Book a Table</h3>
                        <p class="card-text">Reserve your table online and enjoy a seamless dining experience.</p>
                        <a href="../customer/reservation.php" class="btn btn-outline-primary">Book Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fa-solid fa-phone fa-3x mb-3"></i>
                    <div class="card-body">
                        <h3 class="card-title">Order Online</h3>
                        <p class="card-text">Order your favorite dishes online and get them delivered to your doorstep.</p>
                        <a href="../customer/menu.php" class="btn btn-outline-primary">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once('./includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>