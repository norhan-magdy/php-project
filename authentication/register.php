<?php
// Start session
session_start();

// Include UserModel
require_once('../models/UserModel.php');

// Initialize variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate username
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,}$/', $username)) {
        $errors['username'] = 'Username must be at least 3 characters long and contain only letters, numbers, or underscores.';
    }

    // Validate email
    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    // Validate password
    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 6 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password)) {
        $errors['password'] = 'Password must be at least 6 characters long, include a number, and an uppercase letter.';
    }

    // Validate confirm password
    if ($password !== $confirm_password) {
        $errors['confirm_password'] = 'Passwords do not match.';
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        $userModel = new UserModel();

        // Check if username or email already exists
        $existingUser = $userModel->getUserByUsernameOrEmail($username, $email);
        if ($existingUser) {
            if ($existingUser['username'] === $username) {
                $errors['username'] = 'Username already taken.';
            }
            if ($existingUser['email'] === $email) {
                $errors['email'] = 'Email already registered.';
            }
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Register the user
            $user_id = $userModel->registerUser($username, $hashed_password, $email);

            if ($user_id) {
                // Auto-login the user
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'customer'; // Default role

                // Redirect to login page
                header('Location: login.php?registered=success');
                exit;
            } else {
                $errors['general'] = 'Registration failed. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

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

        .navbar-brand, .nav-link {
            color: white !important;
        }

        .nav-link:hover {
            color: #ffc107 !important;
        }

        /* Registration Card */
        .register-container {
            max-width: 400px;
            width: 100%;
            padding: 30px;
            margin: 150px auto 30px;
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

    <!-- Registration Form -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="register-container">
            <h2 class="text-center fw-bold mb-4">Register</h2>

            <!-- General Error Message -->
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>

            <form method="POST" action="register.php">
                <!-- Username Field -->
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>">
                    <?php if (!empty($errors['username'])): ?>
                        <div class="text-danger small"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger small"><?= $errors['email'] ?></div>
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

                <!-- Confirm Password Field -->
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div class="text-danger small"><?= $errors['confirm_password'] ?></div>
                    <?php endif; ?>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>

            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>

    <!-- Footer -->
    <?php require_once('../includes/footer.php'); ?>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
