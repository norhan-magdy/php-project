<?php
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="../assets/CSS/style.css">
</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
      <div class="logo-cont">
        <a class="navbar-brand" href="../index.php"><img src="../assets/img/Logo.png" alt="logo" class="logo img-fluid"></a>
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link" href="../customer/menu.php">Menu</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../customer/reservation.php">Reservations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../customer/order.php">Order Online</a>
          </li>

          <?php if (isset($_SESSION['username'])): ?>
            <li class="nav-item d-flex align-items-center ms-lg-3">
              <span class="text-light me-2">Welcome, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong>!</span>
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
        </ul>
        
                <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="../customer/order.php   ">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="badge bg-danger" id="cart-counter">
                            <?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?>
                        </span>
                    </a>
                </li>
              
            </ul>
      </div>
    </div>
  </nav>
</body>

</html>