<?php
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // Redirect based on user role
    if ($_SESSION['role'] === 'staff') {
        header('Location: dashboard/index.php');
    } else {
        header('Location: dashboardUser.php');
    }
    exit;
}

// Include the header and UserModel
require_once '../models/UserModel.php';

// Initialize variables
$username = $password = '';
$errors = [];

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validate inputs
    if (empty($username)) {
        $errors['username'] = 'Username is required.';
    }

    if (empty($password)) {
        $errors['password'] = 'Password is required.';
    }

    // If no errors, proceed with login
    if (empty($errors)) {
        $userModel = new UserModel();

        // Check if the user exists
        $user = $userModel->getUserByUsername($username);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Login successful
                // Store user data in the session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

         // Redirect based on user role
         if ($user['role'] === 'staff') {
            header('Location: dashboard/index.php');
        } else {
            header('Location: dashboardUser.php');
        }
        exit;
    } else {
        $errors['general'] = 'Invalid username or password.';
    }
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/CSS/style1.css">
</head>
<?php require_once ('../includes/header.php');   ?>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-4 shadow" style="max-width: 400px; width: 100%;">
            <h2 class="text-center mb-4">Login</h2>
            <?php if (!empty($errors['general'])): ?>
                <div class="alert alert-danger"><?= $errors['general'] ?></div>
            <?php endif; ?>
            <form method="POST" action="login.php">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($username) ?>">
                    <?php if (!empty($errors['username'])): ?>
                        <div class="text-danger"><?= $errors['username'] ?></div>
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control">
                    <?php if (!empty($errors['password'])): ?>
                        <div class="text-danger"><?= $errors['password'] ?></div>
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
            <p class="mt-3 text-center">Don't have an account? <a href="register.php">Register here</a>.</p>
        </div>
    </div>
    <?php require_once ('../includes/footer.php');   ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>