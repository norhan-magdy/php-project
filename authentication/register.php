<?php
// Start the session
session_start();

// Include the header and UserModel
// require_once 'header.php';
require_once '../models/UserModel.php';

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
                header('Location: index.php');
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
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <h2>Register</h2>
    <?php if (!empty($errors['general'])): ?>
        <p style="color: red;"><?= $errors['general'] ?></p>
    <?php endif; ?>
    <form method="POST" action="register.php">
        <div>
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" value="<?= htmlspecialchars($username) ?>">
            <?php if (!empty($errors['username'])): ?>
                <span style="color: red;"><?= $errors['username'] ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email) ?>">
            <?php if (!empty($errors['email'])): ?>
                <span style="color: red;"><?= $errors['email'] ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password">
            <?php if (!empty($errors['password'])): ?>
                <span style="color: red;"><?= $errors['password'] ?></span>
            <?php endif; ?>
        </div>
        <div>
            <label for="confirm_password">Confirm Password:</label>
            <input type="password" name="confirm_password" id="confirm_password">
            <?php if (!empty($errors['confirm_password'])): ?>
                <span style="color: red;"><?= $errors['confirm_password'] ?></span>
            <?php endif; ?>
        </div>
        <button type="submit">Register</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a>.</p>
</body>
</html>