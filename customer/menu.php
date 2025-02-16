<?php
// Start the session
session_start();

// Include the header and models
require_once '../models/CategoryModel.php';
require_once '../models/DishModel.php';
require_once '../models/SpecialOfferModel.php';
require_once '../models/CartModel.php';

// Initialize models
$categoryModel = new CategoryModel();
$dishModel = new DishModel();
$specialOfferModel = new SpecialOfferModel($conn); // Initialize the SpecialOfferModel

// Fetch all active categories
$categories = $categoryModel->getAllCategories();

// Fetch all active special offers
$specialOffers = $specialOfferModel->getAllSpecialOffers();


// Handle "Add to Cart" form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $dish_id = $_POST['dish_id'];
    $dish_name = $_POST['dish_name'];
    $dish_price = $_POST['dish_price'];
    $quantity = $_POST['quantity'];

    // Add the item to the cart
    CartModel::addToCart($dish_id, $dish_name, $dish_price, $quantity);

    // Redirect back to the menu page
    header('Location: menu.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/style.css">
    
    <style>
        /* Navbar */
        .navbar {
            background-color: #343a40 !important;
        }

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        /* Page Styling */
        body {
            background: #f8f9fa;
        }

        .menu-section {
            margin: 80px auto 40px;
            padding: 20px;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Sidebar Styling */
        .sidebar {
            position: fixed;
            top: 70px; /* Adjust based on header height */
            left: 0;
            height: calc(100vh - 70px); /* Full height minus header */
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            overflow-y: auto; /* Enable scrolling if content overflows */
        }

        .sidebar h3 {
            color: #ffc107;
            margin-bottom: 20px;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin-bottom: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            transition: all 0.3s ease;
            padding: 8px 12px;
            border-radius: 8px;
            display: block;
        }

        .sidebar ul li a:hover {
            color: #ffc107;
            background-color: rgba(255, 255, 255, 0.1);
        }

        .sidebar ul li a.active {
            color: #ffc107;
            background-color: rgba(255, 255, 255, 0.1);
            font-weight: bold;
            border-left: 4px solid #ffc107; /* Add a left border for active state */
        }

        /* Main Content Styling */
        .main-content {
            margin: 80px 50px 0 270px; /* Same as sidebar width */
            padding: 20px;
        }

        /* Card Styling */
        .card {
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
            border: none;
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .card img {
            height: 280px;
            object-fit: cover;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: #343a40;
        }

        .card-text {
            color: #6c757d;
        }

        .badge {
            font-size: 14px;
            padding: 8px;
            border-radius: 8px;
        }

        /* Category Section Styling */
        .category-section {
            margin-bottom: 40px;
        }

        .category-section h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            position: relative;
        }

        .category-section h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 50px;
            height: 4px;
            background-color: #ffc107;
            border-radius: 2px;
        }

        /* Special Offers Section Styling */
        .special-offer-section {
            margin-bottom: 40px;
        }

        .special-offer-section h2 {
            font-size: 2rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 20px;
            position: relative;
        }

        .special-offer-section h2::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: -10px;
            width: 50px;
            height: 4px;
            background-color: #ffc107;
            border-radius: 2px;
        }
    </style>
</head>

<body>

    <!-- Header -->
    <?php require_once('../includes/header.php'); ?>

    <!-- Sidebar -->
    <div class="sidebar">
        <h3>Categories</h3>
        <ul>
            <li>
                <a href="#special-offers" class="category-link" data-target="special-offers">
                    Special Offers
                </a>
            </li>
            <?php foreach ($categories as $category): ?>
                <li>
                    <a href="#category-<?= htmlspecialchars($category['id']) ?>" class="category-link" data-target="category-<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Special Offers Section -->
        <div id="special-offers" class="special-offer-section">
            <h2>Special Offers</h2>
            <div class="row g-4">
                <?php foreach ($specialOffers as $offer): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <img src="../assets/img/Logo.png" class="card-img-top" alt="offer">
                                <h5 class="card-title"><?= htmlspecialchars($offer['name']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($offer['description']) ?></p>
                                <p class="card-text fw-bold text-success">
                                    Discount: <?= htmlspecialchars($offer['discount']) ?>%
                                </p>
                                <p class="card-text">
                                    Expires on: <?= htmlspecialchars($offer['expiry_date']) ?>
                                </p>
                                           <!-- Add to Cart Form -->
                        <form action="menu.php" method="POST" class="mt-3">
                            <input type="hidden" name="dish_id" value="<?= $offer['id'] ?>">
                            <input type="hidden" name="dish_name" value="<?= $offer['name'] ?>">
                            <input type="hidden" name="dish_price" value="0"> <!-- Special offers are free or discounted -->
                            <input type="hidden" name="add_to_cart" value="1">
                            <div class="input-group mb-3">
                                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            </div>
                        </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Category Sections -->
        <?php foreach ($categories as $category): ?>
            <div id="category-<?= htmlspecialchars($category['id']) ?>" class="category-section">
                <h2><?= htmlspecialchars($category['name']) ?></h2>
                <div class="row g-4">
                    <?php
                    // Fetch dishes for this category
                    $dishes = $dishModel->getDishesByCategory($category['id']);
                    foreach ($dishes as $dish):
                    ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= htmlspecialchars($dish['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($dish['name']) ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($dish['name']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars($dish['description']) ?></p>
                                    <p class="card-text fw-bold text-success">Price: $<?= number_format($dish['price'], 2) ?></p>
                                    <span class="badge <?= $dish['availability'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $dish['availability'] ? 'Available' : 'Not Available' ?>
                                    </span>
                                      <!-- Add to Cart Form -->
                                      <form action="menu.php" method="POST" class="mt-3">
                                        <input type="hidden" name="dish_id" value="<?= $dish['id'] ?>">
                                        <input type="hidden" name="dish_name" value="<?= $dish['name'] ?>">
                                        <input type="hidden" name="dish_price" value="<?= $dish['price'] ?>">
                                        <input type="hidden" name="add_to_cart" value="1">
                                        <div class="input-group mb-3">
                                            <input type="number" name="quantity" class="form-control" value="1" min="1" required>
                                            <button type="submit" class="btn btn-primary">Add to Cart</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript for Active Category Highlighting -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const categoryLinks = document.querySelectorAll('.sidebar ul li a');
            const categorySections = document.querySelectorAll('.category-section, .special-offer-section');

            // Function to highlight the active category
            function highlightActiveCategory() {
                let currentSection = null;

                // Find the section currently in view
                categorySections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (window.scrollY >= sectionTop - 100 && window.scrollY < sectionTop + sectionHeight - 100) {
                        currentSection = section;
                    }
                });

                // Remove active class from all links
                categoryLinks.forEach(link => {
                    link.classList.remove('active');
                });

                // Add active class to the current section's link
                if (currentSection) {
                    const targetLink = document.querySelector(`.sidebar ul li a[data-target="${currentSection.id}"]`);
                    if (targetLink) {
                        targetLink.classList.add('active');
                    }
                }
            }

            // Highlight the active category on scroll
            window.addEventListener('scroll', highlightActiveCategory);

            // Highlight the active category on page load
            highlightActiveCategory();

            // Smooth scroll to the target section when a sidebar link is clicked
            categoryLinks.forEach(link => {
                link.addEventListener('click', function (e) {
                    e.preventDefault(); // Prevent default anchor behavior
                    const targetId = this.getAttribute('data-target');
                    const targetSection = document.getElementById(targetId);

                    if (targetSection) {
                        // Scroll to the target section smoothly
                        targetSection.scrollIntoView({ behavior: 'smooth' });

                        // Highlight the active link
                        categoryLinks.forEach(link => link.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>