<?php
session_start();
require '../models/UserModel.php';

// التأكد من تسجيل دخول المستخدم
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$userModel = new UserModel();
$user_id = $_SESSION['user_id'];
$user = $userModel->getUserById($user_id);

if (!$user) {
    die("User not found.");
}

// تحديد مسار الصورة الافتراضية إذا لم يكن هناك صورة مرفوعة
$profile_picture = !empty($user['profile_picture']) ? '../uploads/' . $user['profile_picture'] : '../uploads/default.png';

// التأكد من وجود بيانات لكل حقل
$phone = !empty($user['phone']) ? $user['phone'] : 'Not provided';
$address = !empty($user['address']) ? $user['address'] : 'Not provided';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .profile-card {
            max-width: 500px;
            margin: 180px auto ;
            text-align: center;
            background: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-picture {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #007bff;
        }
        .btn-custom {
            width: 100%;
        }
    </style>
</head>
<body class="bg-light">

<?php require_once('../includes/header.php'); ?>

<div class="container mt-5">
    <div class="profile-card">
        <img src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="profile-picture mb-3">
        <h3 class="fw-bold"><?php echo htmlspecialchars($user['username']); ?></h3>
        <p class="text-muted">📧 <?php echo htmlspecialchars($user['email']); ?></p>
        <p class="text-muted">📞 <?php echo htmlspecialchars($phone); ?></p>
        <p class="text-muted">📍 <?php echo htmlspecialchars($address); ?></p>
        
        <a href="edit_profile.php" class="btn btn-primary btn-custom mt-3">Edit Profile</a>
    </div>
</div>

    <a class="nav-link" href="order_history.php">Order History</a>


<?php require_once('../includes/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
