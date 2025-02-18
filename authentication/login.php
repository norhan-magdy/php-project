<?php
// Start session
session_start();

// Include UserModel
require_once '../models/UserModel.php';

// Initialize variables
$username = $password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username)) $errors['username'] = 'Username is required.';
    if (empty($password)) $errors['password'] = 'Password is required.';

    if (empty($errors)) {
        $userModel = new UserModel();
        $user = $userModel->getUserByUsername($username);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: ../index.php ');
            exit;
        } else {
            $errors['general'] = 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/style.css">

    <style>
        /* Global Styles */
        body {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            height: 100vh;
        }

        /* Navbar */
        .navbar {
            background-color: #343a40 !important;
        }

        .navbar-brand,
        .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        /* Login Card */
        .login-container {
            max-width: 400px;
            width: 100%;
            margin: 200px auto 150px;
            padding: 30px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Form Fields */
        .form-control {
            border-radius: 6px;
            border: 1px solid #ced4da;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.25);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <?php require_once('../includes/header.php'); ?>

    <!-- Login Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="login-container">
            <h2 class="text-center fw-bold mb-4">Login</h2>

            <!-- General Error Message -->
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <!-- Username Field -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
                    <?php if (!empty($errors['username'])): ?>
                        <div class="text-danger small"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger small"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>