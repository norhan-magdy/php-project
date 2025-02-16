<?php
session_start();
require_once '../models/CategoryModel.php';
require_once '../models/DishModel.php';
require_once '../models/SpecialOfferModel.php';
require_once '../models/CartModel.php';

$categoryModel = new CategoryModel();
$dishModel = new DishModel();
$specialOfferModel = new SpecialOfferModel($conn);

$totalItems = count($dishes);
$averagePrice = $dishModel->getAveragePrice();
$availableItems = $dishModel->countAvailableItems();

require_once('../includes/header.php');
?>
<div class="container">
  <div class="row flex-nowrap">
    <div class="col-auto col-md-3 col-xl-2 px-sm-2 px-0 ">
      <?php require_once('./sidebar.php'); ?>
    </div>
    <div class="col py-5 mt-5">
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container-fluid">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler">
            <span class="navbar-toggler-icon"></span>
          </button>
          <a class="navbar-brand" href="#">Restaurant Dashboard</a>
        </div>
      </nav>
      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-egg-fried"></i> Total Items</h5>
              <p class="display-4"><?= $totalItems ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-currency-dollar"></i> Average Price</h5>
              <p class="display-4">$<?= number_format($averagePrice, 2) ?></p>
            </div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card shadow">
            <div class="card-body">
              <h5 class="card-title"><i class="bi bi-check2-circle"></i> Available Items</h5>
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