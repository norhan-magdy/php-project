<?php
session_start();
// Include the database configuration and models
require_once '../conf/conf.php';
require_once '../models/SpecialOfferModel.php';
require_once '../controller/CartModel.php';

// Initialize the model with the database connection
$offerModel = new SpecialOfferModel($conn);

// Fetch POST data
$offer_id = 1; // Example: Buy One Get One Free on Pizzas
$first_pizza_id = isset($_POST['first_pizza']) ? (int)$_POST['first_pizza'] : null;
$second_pizza_id = isset($_POST['second_pizza']) ? (int)$_POST['second_pizza'] : null;

// Validate the selected pizzas
if (!$offerModel->isMenuItemEligibleForOffer($offer_id, $first_pizza_id)) {
    die("Error: Invalid first pizza selection.");
}

// Define the subset of menu items for the second pizza
$secondPizzaSubsetIds = [2, 3, 4]; // Example: IDs 2, 3, and 4 are allowed for the second pizza

if (!in_array($second_pizza_id, $secondPizzaSubsetIds)) {
    die("Error: Invalid second pizza selection.");
}

// Fetch the prices of the selected pizzas
$first_pizza_price = $offerModel->getMenuItemPrice($first_pizza_id);
$second_pizza_price = $offerModel->getMenuItemPrice($second_pizza_id);

if ($first_pizza_price === null || $second_pizza_price === null) {
    die("Error: Unable to fetch pizza prices.");
}

// Apply the offer logic here (e.g., calculate discount, update order, etc.)
$offer = $offerModel->getSpecialOfferById($offer_id);

if ($offer) {
    // Calculate the total price before and after the discount
    $total_before_discount = $first_pizza_price + $second_pizza_price;
    $discount_amount = ($total_before_discount * $offer['discount']) / 100;
    $total_after_discount = $total_before_discount - $discount_amount;

    // Adjust prices based on the discount
    $first_pizza_discounted_price = $first_pizza_price - ($discount_amount / 2);
    $second_pizza_discounted_price = $second_pizza_price - ($discount_amount / 2);

    // Fetch the names of the selected pizzas
    $first_pizza_name = $offerModel->getMenuItemName($first_pizza_id);
    $second_pizza_name = $offerModel->getMenuItemName($second_pizza_id);

    if ($first_pizza_name === null || $second_pizza_name === null) {
        die("Error: Unable to fetch pizza names.");
    }

    // Add the discounted pizzas to the cart
    CartModel::addToCart($first_pizza_id, $first_pizza_name, $first_pizza_discounted_price, 1);
    CartModel::addToCart($second_pizza_id, $second_pizza_name, $second_pizza_discounted_price, 1);

    // Store the results in session to pass to the next page
    $_SESSION['offer_result'] = [
        'total_before_discount' => $total_before_discount,
        'discount_amount' => $discount_amount,
        'total_after_discount' => $total_after_discount,
        'first_pizza_name' => $first_pizza_name,
        'first_pizza_discounted_price' => $first_pizza_discounted_price,
        'second_pizza_name' => $second_pizza_name,
        'second_pizza_discounted_price' => $second_pizza_discounted_price
    ];

    // Redirect to the result page
    header('Location: ../customer/offer_result.php');
    exit();
} else {
    die("Error: Offer not found.");
}