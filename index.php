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
    <title>Restaurant Application</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <link rel="stylesheet" href="./assets/CSS/style.css">


    <style>
        /* Hero Section Styling */
        .hero-section {
            background: url('../assets/img/hero.jpg') no-repeat center center/cover;
            height: 100vh;
            /* Full Screen Height */
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

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
            /* Hover Effect */
        }

        /* Card Hover Effect */
        .card:hover {
            transform: translateY(-5px);
            transition: 0.3s ease-in-out;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .hero-section {
                height: 60vh;
                /* Smaller Hero Section on Mobile */
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php require_once('./includes/header.php'); ?>
    

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="text-center">
            <h1 class="display-3 fw-bold">Welcome to Our Restaurant</h1>
            <img src="../assets/img/Logo.png" alt="logo" class="img-fluid" style="width: 600px; box-shadow: 0 0 20px rgba(0, 0, 0, 0.5); background-color:rgba(52, 58, 64, 0.49); border-radius: 15%;">
            <p class="lead">Delicious food, great ambiance, and exceptional service.</p>
            <a href="/customer/menu.php" class="btn btn-warning btn-lg">Explore Menu</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container my-5">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fas fa-pizza-slice fa-3x mb-3" style="color: #ff7800;"></i>
                    <div class="card-body">
                        <h3 class="card-title">Our Menu</h3>
                        <p class="card-text">Discover our wide range of dishes, from appetizers to desserts.</p>
                        <a href="../customer/menu.php" class="btn btn-outline-primary">View Menu</a>
                    </div>
                </div>
            </div>

             <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fa-solid fa-utensils fa-3x mb-3" style="color: #ff7800;"></i>
                    <div class="card-body">
                        <h3 class="card-title">Book a Table</h3>
                        <p class="card-text">Reserve your table online and enjoy a seamless dining experience.</p>
                        <a href="../customer/reservation.php" class="btn btn-outline-primary">Book Now</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card text-center shadow-lg p-3">
                    <i class="fa-solid fa-phone fa-3x mb-3" style="color: #ff7800;"></i>
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