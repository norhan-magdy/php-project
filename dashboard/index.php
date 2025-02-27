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
<div class="page d-flex">
  <?php require_once('./sidebar.php'); ?>
  <div class="content w-full bg-light">
    <div class="head bg-red c-white p-15 between-flex">
      <h2 class="m-0">
        <i class="fa-solid fa-chart-line mr-10"></i>
        Restaurant Dashboard
      </h2>
      <div class="d-flex align-center">
        <span class="fs-14"><?= date('F j, Y') ?></span>
      </div>
    </div>

    <div class="wrapper d-grid gap-20 p-20">
      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Total Sales</h3>
            <p class="m-0 fs-14 c-grey">All-time revenue</p>
          </div>
          <i class="fa-solid fa-coins fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20">$<?= number_format($totalSales, 2) ?></h1>
      </div>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Active Orders</h3>
            <p class="m-0 fs-14 c-grey">Currently processing</p>
          </div>
          <i class="fa-solid fa-utensils fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20"><?= $activeOrders ?></h1>
      </div>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Reservations</h3>
            <p class="m-0 fs-14 c-grey">Confirmed bookings</p>
          </div>
          <i class="fa-regular fa-calendar-check fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20"><?= $reservations ?></h1>
      </div>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Available Items</h3>
            <p class="m-0 fs-14 c-grey">Menu items in stock</p>
          </div>
          <i class="fa-solid fa-pizza-slice fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20"><?= $availableItems ?></h1>
      </div>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Average Price</h3>
            <p class="m-0 fs-14 c-grey">Per menu item</p>
          </div>
          <i class="fa-solid fa-dollar-sign fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20">$<?= number_format($averagePrice, 2) ?></h1>
      </div>

      <div class="dashboard-card bg-white rad-10 p-20 border-top-red">
        <div class="between-flex">
          <div>
            <h3 class="mt-0 mb-5 c-red">Total Items</h3>
            <p class="m-0 fs-14 c-grey">Menu variety</p>
          </div>
          <i class="fa-solid fa-utensils fa-2x c-red"></i>
        </div>
        <h1 class="c-black mt-20"><?= $totalItems ?></h1>
      </div>
    </div>
  </div>
</div>

<style>
  .border-top-red {
    border-top: 4px solid var(--red-color);
  }

  .dashboard-card {
    transition: transform 0.3s;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
  }
</style>