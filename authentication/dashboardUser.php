<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restaurant Application</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">

    <style>
        /* Hero Section Styling */
        .hero-section {
            background: url('../assets/img/hero.jpg') no-repeat center center/cover;
            height: 100vh; /* Full Screen Height */
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        /* Navbar Customization */
        .navbar {
            background-color: #343a40 !important;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important; /* Hover Effect */
        }

        /* Card Hover Effect */
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s ease-in-out;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh; /* Smaller Hero Section on Mobile */
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand" href="#">
                <img src="../assets/img/Logo.png" alt="logo" class="img-fluid" style="height: 40px;">
            </a>

            <!-- Mobile Toggle Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar Links -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#menu">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#reservations">Reservations</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#order">Order Online</a>
                    </li>

                    <!-- User Greeting & Logout -->
                    <li class="nav-item d-flex align-items-center ms-lg-3">
                        <span class="text-light me-2">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</span>
                        <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="text-center">
            <h1 class="display-3 fw-bold">Welcome to Our Restaurant</h1>
            <p class="lead">Delicious food, great ambiance, and exceptional service.</p>
            <a href="#menu" class="btn btn-warning btn-lg">Explore Menu</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <img src="../assets/img/menu.jpg" class="card-img-top" alt="Menu">
                    <div class="card-body">
                        <h3 class="card-title">Our Menu</h3>
                        <p class="card-text">Discover our wide range of dishes, from appetizers to desserts.</p>
                        <a href="#menu" class="btn btn-outline-primary">View Menu</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <img src="../assets/img/reservation.jpg" class="card-img-top" alt="Reservations">
                    <div class="card-body">
                        <h3 class="card-title">Book a Table</h3>
                        <p class="card-text">Reserve your table online and enjoy a seamless dining experience.</p>
                        <a href="#reservations" class="btn btn-outline-success">Book Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <img src="../assets/img/order.jpg" class="card-img-top" alt="Order Online">
                    <div class="card-body">
                        <h3 class="card-title">Order Online</h3>
                        <p class="card-text">Order your favorite dishes online and get them delivered to your doorstep.</p>
                        <a href="#order" class="btn btn-outline-danger">Order Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
