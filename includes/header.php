<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/CSS/style.css">
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
      <!-- Logo -->
      <a class="navbar-brand" href="../index.php">
        <img src="../assets/img/Logo.png" alt="logo" class="logo img-fluid" style="height: 50px;">
      </a>

      <!-- Toggle Button -->
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <!-- Navbar Links -->
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto align-items-center">
          <li class="nav-item">
            <a class="nav-link" href="../customer/menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../customer/reservation.php">Reservations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../customer/order.php">Order Online</a>
          </li>

          <!-- User Authentication -->
          <?php if (isset($_SESSION['username'])): ?>
            <li class="nav-item d-flex align-items-center ms-lg-3">
              <span class="text-light me-2">Hello, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong></span>
              <a href="../authentication/logout.php" class="btn btn-danger btn-sm">Logout</a>
            </li>
          <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="../authentication/login.php">Login</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../authentication/register.php">Register</a>
            </li>
          <?php endif; ?>

          <!-- Shopping Cart -->
          <li class="nav-item ms-lg-3">
            <a class="nav-link position-relative" href="../customer/order.php">
              <i class="fas fa-shopping-cart fa-lg"></i>
              <span class="badge bg-danger rounded-circle position-absolute top-0 start-100 translate-middle p-1"
                style="font-size: 12px; min-width: 18px; min-height: 18px; display: flex; align-items: center; justify-content: center;">
                <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
              </span>
            </a>
          </li>

          <!-- Staff Dashboard (Visible Only for Staff) -->
          <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'staff'): ?>
            <li class="nav-item ms-lg-3">
              <a class="nav-link" href="../dashboard/index.php">
                <i class="fas fa-chart-line"></i> Dashboard
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>