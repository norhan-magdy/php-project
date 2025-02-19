<?php
include_once '../conf/conf.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $table_number = $_POST['table_number'];
    $reservation_date = $_POST['reservation_date'];
    $reservation_time = $_POST['reservation_time'];
    $user_id = 2; // Replace with actual user ID
    $guests = $_POST['guests']; // Get the number of guests from the form

    // Combine date and time into one datetime string
    $reservation_datetime = $reservation_date . ' ' . $reservation_time;

    // Check if the table is already reserved
    $stmt = $conn->prepare("SELECT * FROM reservations WHERE table_id = (SELECT id FROM tables WHERE table_number = ?) AND reservation_date = ?");
    $stmt->bind_param("is", $table_number, $reservation_datetime);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch all tables and their reservation status for the selected date
        $tables_sql = "SELECT t.table_number, t.capacity, t.location, r.reservation_date 
                       FROM tables t 
                       LEFT JOIN reservations r ON t.id = r.table_id AND r.reservation_date = ?
                       ORDER BY t.table_number";
        $stmt = $conn->prepare($tables_sql);
        $stmt->bind_param("s", $reservation_datetime);
        $stmt->execute();
        $tables_result = $stmt->get_result();
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Table Reservation Status</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background-color: #f8f9fa;
                    padding: 20px;
                }
                .container {
                    max-width: 800px;
                    margin: 50px auto;
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                }
                h2 {
                    color: red;
                    text-align: center;
                    margin-bottom: 20px;
                }
                .table {
                    width: 100%;
                    margin-bottom: 20px;
                    border-collapse: collapse;
                }
                .table th, .table td {
                    padding: 12px;
                    text-align: center;
                    border: 1px solid #ddd;
                }
                .table th {
                    background-color: #007bff;
                    color: white;
                }
                .table tr:nth-child(even) {
                    background-color: #f2f2f2;
                }
                .table tr:hover {
                    background-color: #ddd;
                }
                .btn-back {
                    display: block;
                    width: 200px;
                    margin: 20px auto;
                    text-align: center;
                    padding: 10px;
                    background-color: #007bff;
                    color: white;
                    text-decoration: none;
                    border-radius: 5px;
                }
                .btn-back:hover {
                    background-color: #0056b3;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h2>This table is already reserved.</h2>
                <p style="text-align: center;">Please choose another table from the available ones below:</p>
                
                <table class="table">
                    <thead>
                        <tr>
                            <th>Table Number</th>
                            <th>Capacity</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($table = $tables_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $table['table_number']; ?></td>
                                <td><?php echo $table['capacity']; ?></td>
                                <td><?php echo $table['location']; ?></td>
                                <td>
                                    <?php if ($table['reservation_date']): ?>
                                        <span style="color: red;">Reserved</span>
                                    <?php else: ?>
                                        <span style="color: green;">Available</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>

                <a href="reservation.php" class="btn-back">Back to Book a Table</a>
            </div>
        </body>
        </html>
        <?php
    } else {
        // Insert the reservation
        $stmt = $conn->prepare("INSERT INTO reservations (user_id, table_id, reservation_date, guests) VALUES (?, ?, ?, ?)");
        $table_id = (int) $conn->query("SELECT id FROM tables WHERE table_number = $table_number")->fetch_row()[0]; // Get table ID
        $stmt->bind_param("iisi", $user_id, $table_id, $reservation_datetime, $guests);
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