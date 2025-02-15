<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant Application</title>
  <!-- Bootstrap CSS -->

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="./assets/CSS/style.css">
  <!-- <link href=""stylesheet"> -->
  <!-- Custom CSS (optional) -->
  <style>
    .hero-section {
      background: url('./assets/img/hero.jpg') no-repeat center center/cover;
      height: 400px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #FFF;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
    }
    .navbar {
      background-color: #343a40 !important;
    }
    .navbar-brand, .nav-link {
      color: white !important;
    }
    .nav-link:hover {
      color: #ffc107 !important;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  
  <?php
    require_once ('./includes/header.php')
    ?>
  <!-- Hero Section -->
  <div class="hero-section">
    <div class="text-center">
      <h1 class="display-4">Welcome to Our Restaurant</h1>
      <p class="lead">Delicious food, great ambiance, and exceptional service.</p>
      <a href="#menu" class="btn btn-warning btn-lg">Explore Menu</a>
    </div>
  </div>

  <!-- Main Content -->
  <div class="container mt-5">
    <div class="row">
      <div class="col-md-4 text-center">
        <h3>Our Menu</h3>
        <p>Discover our wide range of dishes, from appetizers to desserts.</p>
        <a href="#menu" class="btn btn-outline-primary">View Menu</a>
      </div>
      <div class="col-md-4 text-center">
        <h3>Book a Table</h3>
        <p>Reserve your table online and enjoy a seamless dining experience.</p>
        <a href="#reservations" class="btn btn-outline-success">Book Now</a>
      </div>
      <div class="col-md-4 text-center">
        <h3>Order Online</h3>
        <p>Order your favorite dishes online and get them delivered to your doorstep.</p>
        <a href="#order" class="btn btn-outline-danger">Order Now</a>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <?php
    require_once ('./includes/footer.php')
    ?>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>