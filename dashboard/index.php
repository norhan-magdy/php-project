<?php
session_start();
require_once '../models/CategoryModel.php';
require_once '../models/DishModel.php';
require_once '../models/SpecialOfferModel.php';
require_once '../controller/CartModel.php';

$categoryModel = new CategoryModel();
$dishModel = new DishModel();
$specialOfferModel = new SpecialOfferModel($conn);

$dishes = $dishModel->getAllDishes();

// Existing metrics
$totalItems = count($dishes);
$averagePrice = $dishModel->getAveragePrice();
$availableItems = $dishModel->countAvailableItems();

$totalSalesResult = $conn->query("SELECT SUM(total_price) AS total_sales FROM orders WHERE payment_status = 'paid'");
$totalSalesRow = $totalSalesResult->fetch_assoc();
$totalSales = $totalSalesRow['total_sales'] ?? 0;

$activeOrdersResult = $conn->query("SELECT COUNT(*) AS active_orders FROM orders WHERE status IN ('pending', 'preparing', 'ready')");
$activeOrdersRow = $activeOrdersResult->fetch_assoc();
$activeOrders = $activeOrdersRow['active_orders'] ?? 0;

$reservationsResult = $conn->query("SELECT COUNT(*) AS confirmed_reservations FROM reservations WHERE status = 'confirmed'");
$reservationsRow = $reservationsResult->fetch_assoc();
$reservations = $reservationsRow['confirmed_reservations'] ?? 0;

require_once('../includes/header.php');
?>
<div class="container">
  <div class="row flex-nowrap">
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 ">
      <?php require_once('./sidebar.php'); ?>
    </div>
    <div class="col py-5 mt-5">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 shadow-lg rounded">
        <div class="container-fluid d-flex align-items-center">
          <h3 class="text-white fw-bold mb-0">
            <i class="fa-solid fa-chart-line me-2"></i> Dashboard
          </h3>
        </div>
      </nav>
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-coins"></i> Total Sales</h5>
              <p class="display-4">$<?= number_format($totalSales, 2) ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-tasks"></i> Active Orders</h5>
              <p class="display-4"><?= $activeOrders ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-calendar-check"></i> Reservations</h5>
              <p class="display-4"><?= $reservations ?></p>
            </div>
          </div>
        </div>
      </div>
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-pizza-slice"></i> Total Items</h5>
              <p class="display-4"><?= $totalItems ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Average Price</h5>
              <p class="display-4">$<?= number_format($averagePrice, 2) ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-check-circle"></i> Available Items</h5>
              <p class="display-4"><?= $availableItems ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>