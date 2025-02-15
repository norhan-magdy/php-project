<?php
// Start the session
session_start();

// Include the header and UserModel
require_once ('../models/UserModel.php');

// Initialize variables
$username = $email = $password = $confirm_password = '';
$errors = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    // Validate inputs
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    } elseif (strlen($username) < 3) {
        $errors['username'] = 'Username must be at least 3 characters long.';
    }

    if (empty($email)) {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $errors['password'] = 'Password must be at least 6 characters long.';
    }

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
                // Registration successful
                // Log the user in automatically
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = 'customer'; // Default role

                // Redirect to the dashboard or home page
                header('Location: login.php');
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/style1.css">
</head>
<body class="bg-light">
    <?php require_once ('../includes/header.php');   ?>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Register</h2>
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>
            <form method="POST" action="register.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username ?? '') ?>">
                    <?php if (!empty($errors['username'])): ?>
                        <div class="text-danger"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($email ?? '') ?>">
                    <?php if (!empty($errors['email'])): ?>
                        <div class="text-danger"><?= $errors['email'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="form-control">
                    <?php if (!empty($errors['confirm_password'])): ?>
                        <div class="text-danger"><?= $errors['confirm_password'] ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary w-100">Register</button>
            </form>
            <p class="mt-3 text-center">Already have an account? <a href="login.php">Login here</a>.</p>
        </div>
    </div>
    <?php require_once ('../includes/footer.php');   ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
