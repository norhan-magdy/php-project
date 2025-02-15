<?php
include_once '../conf/conf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_number = $_POST['table_number'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $user_id = 2; // Replace with actual user ID
    $guests = $_POST['guests']; // Get the number of guests from the form

    // Check if the table is already reserved
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE table_id = (SELECT id FROM tables WHERE table_number = ?) AND reservation_date = ?");
    $stmt->bind_param("is", $table_number, $reservation_date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div style="text-align: center; margin: 50px;">';
        echo '<h2 style="color: red;">This table is already reserved.</h2>';
        echo '<p>Please choose another table.</p>';
        echo '<a href="reservation.php" style="text-decoration: none; padding: 10px 20px; background-color: #007bff; color: white; border-radius: 5px;">Back to book a table</a>';
        echo '</div>';
    } else {
        // Insert the reservation
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, table_id, reservation_date, guests) VALUES (?, ?, ?, ?)");
        $table_id = (int) $conn->query("SELECT id FROM tables WHERE table_number = $table_number")->fetch_row()[0]; // Get table ID
        $stmt->bind_param("iisi", $user_id, $table_id, $reservation_date, $guests);
        $stmt->execute();

        // Update table status
        $stmt = $conn->prepare("UPDATE tables SET status = 'reserved' WHERE id = ?");
        $stmt->bind_param("i", $table_id);
        $stmt->execute();

        header('Location: reservation.php');
        exit();
    }
}
?>

<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 20px;
    }
    h2 {
        margin-bottom: 20px;
    }
    a {
        display: inline-block;
        margin-top: 20px;
    }
</style>