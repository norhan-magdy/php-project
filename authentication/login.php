<?php
// Start session
session_start();

// Include UserModel
require_once '../models/UserModel.php';

// Initialize variables
$email = $password = '';
$errors = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);


    // Validate inputs
    if (empty($username)) $errors['email'] = 'email is required.';
    if (empty($password)) $errors['password'] = 'Password is required.';

    if (empty($errors)) {
        $userModel = new UserModel();
        $user = $userModel->getUserByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];


            header('Location: ../index.php ');
            exit;
        } else {
            $errors['general'] = 'Invalid email or password.';
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
                    <label for="email" class="form-label">Email
                    </label>
                    <input type="text" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email) ?>">
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