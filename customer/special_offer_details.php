<?php
session_start();
// Include the database configuration
require_once '../conf/conf.php';
require_once '../models/SpecialOfferModel.php';

// Initialize the model with the database connection
$offerModel = new SpecialOfferModel($conn);

// Fetch the special offer details
$offer_id = 1; // Example: Buy One Get One Free on Pizzas
$offer = $offerModel->getSpecialOfferWithItems($offer_id);

if (!$offer) {
    die("Offer not found.");
}

// Fetch all eligible menu items for the offer
$allEligibleItems = $offerModel->getEligibleMenuItemsForOffer($offer_id);

// Define the subset of menu items for the second pizza
$secondPizzaSubsetIds = [2, 3, 4]; // Example: IDs 2, 3, and 4 are allowed for the second pizza
$secondPizzaSubset = $offerModel->filterMenuItemsBySubset($allEligibleItems, $secondPizzaSubsetIds);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Special Offer</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="../assets/CSS/style.css">


    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .card {
            margin: 180px auto;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <?php require_once('../includes/header.php'); ?>


<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><?php echo htmlspecialchars($offer['name']); ?></h3>
                </div>
                <div class="card-body">
                    <p><?php echo nl2br(htmlspecialchars($offer['description'])); ?></p>
                    <form method="post" action="../controller/process_offer.php" class="needs-validation" novalidate>
                        <div class="mb-3">
                            <label for="first_pizza" class="form-label">Select Your First Pizza (Any Pizza):</label>
                            <select id="first_pizza" name="first_pizza" class="form-select" required>
                                <option value="" disabled selected>Select a pizza</option>
                                <?php foreach ($allEligibleItems as $item): ?>
                                    <option value="<?php echo htmlspecialchars($item['id']); ?>">
                                        <?php echo htmlspecialchars($item['name']); ?> - <?php echo htmlspecialchars($item['price']); ?> EGP
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a pizza.</div>
                        </div>

                        <div class="mb-3">
                            <label for="second_pizza" class="form-label">Select Your Second Pizza (Limited Options):</label>
                            <select id="second_pizza" name="second_pizza" class="form-select" required>
                                <option value="" disabled selected>Select a pizza</option>
                                <?php foreach ($secondPizzaSubset as $item): ?>
                                    <option value="<?php echo htmlspecialchars($item['id']); ?>">
                                        <?php echo htmlspecialchars($item['name']); ?> - <?php echo htmlspecialchars($item['price']); ?> EGP
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Please select a pizza.</div>
                        </div>

                        <button type="submit" class="btn btn-success w-100">Apply Offer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once('../includes/footer.php'); ?>

<!-- Bootstrap JS and dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Form validation
    (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    })();
</script>

</body>
</html>