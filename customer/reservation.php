<?php
include_once '../conf/conf.php';
session_start();

if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'staff') {
    header('Location: ../dashboard/index.php');
    exit;
}

$sql = "SELECT table_number, capacity, location FROM tables";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Table Reservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/CSS/style.css">
    <style>
        body {
            background-color: #e9ecef; /* لون خلفية جديد */
            padding-top: 70px;
        }
        .order-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .table-map {
            text-align: center;
            margin-bottom: 20px;
        }
        .table-map img {
            width: 100%;
            max-width: 800px; /* تكبير حجم الصورة */
            cursor: pointer;
            border: 2px solid #ddd;
            border-radius: 10px;
            transition: border-color 0.3s;
        }
        .table-map img:hover {
            border-color: #007bff;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 20px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        @media (max-width: 768px) {
            .order-container {
                margin: 20px auto;
                padding: 15px;
            }
            .table-map img {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
<?php require_once('../includes/header.php'); ?>

<div class="container custom-container">
    <h2>Table Reservation</h2>
    
    <div class="table-map">
        <img src="../assets/img/floors.jpg" alt="Restaurant Floors" onclick="selectTable()">
    </div>

    <h3>Reserve a Table</h3>
    <form method="POST" action="reserve.php">
        <div class="form-group">
            <label for="guests">Number of Guests:</label>
            <input type="number" name="guests" class="form-control" required min="1" max="60" onblur="filterTables(this.value)">
        </div>
        <div class="form-group">
            <label for="table_number">Table Number:</label>
            <input type="number" name="table_number" class="form-control" required min="1" max="10">
        </div>
        <div class="form-group">
            <label for="reservation_date">Select Date:</label>
            <input type="date" name="reservation_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="reservation_time">Select Time:</label>
            <input type="time" name="reservation_time" class="form-control" >
        </div>
        <button type="submit" class="btn btn-primary">Confirm Reservation</button>
    </form>
</div>
<?php require_once('../includes/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script>
    function selectTable() {
        alert('You can select a table from the image.');
        // يمكنك إضافة منطق إضافي هنا للتعامل مع اختيار الطاولة من الصورة
    }

    function filterTables(guests) {
        alert('Filtering tables for ' + guests + ' guests');
        // يمكنك إضافة منطق هنا لتصفية الطاولات بناءً على عدد الضيوف
    }
</script>
</body>
</html>