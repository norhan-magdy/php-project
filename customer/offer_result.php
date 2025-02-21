<?php
session_start();

// Check if the offer result is available in the session
if (!isset($_SESSION['offer_result'])) {
    die("Error: No offer result found. Please go back and try again.");
}

// Fetch the results from the session
$result = $_SESSION['offer_result'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer Applied</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
      .coco{
        padding: 200px;
      }
        </style>
</head>

<body>
<?php require_once('../includes/header.php'); ?>

    <div class="container mt-5 coco">
        <div class="alert alert-success text-center">
            <h4>Offer Applied Successfully!</h4>
            <p>Your total before discount: <strong><?php echo number_format($result['total_before_discount'], 2); ?> EGP</strong></p>
            <p>Discount applied: <strong><?php echo number_format($result['discount_amount'], 2); ?> EGP</strong></p>
            <p>Your total after discount: <strong><?php echo number_format($result['total_after_discount'], 2); ?> EGP</strong></p>
            <p>Pizzas added to your cart:</p>
               <div class="d-flex flex-column">
               <span><?php echo htmlspecialchars($result['first_pizza_name']); ?> - <?php echo number_format($result['first_pizza_discounted_price'], 2); ?> USD</span> 
               <span><?php echo htmlspecialchars($result['second_pizza_name']); ?> - <?php echo number_format($result['second_pizza_discounted_price'], 2); ?> USD</span> 
               </div>
               
        </div>
        <a href="order.php" class="btn btn-primary">View Cart</a>
        <a href="special_offer_details.php" class="btn btn-secondary">Back to Offers</a>
    </div>
    <?php require_once('../includes/footer.php'); ?>

</body>
</html>