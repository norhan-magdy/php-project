<?php
require_once '../conf/conf.php';

class ReservationModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }



    // Get all reservations
    public function getAllReservations()
    {
        $sql = "SELECT * FROM reservations";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get reservations by user ID
    public function getReservationsByUserId($user_id)
    {
        $sql = "SELECT * FROM reservations WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update reservation status
    public function updateReservationStatus($id, $status)
    {
        $sql = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    // Delete a reservation
    public function deleteReservation($id)
    {
        $sql = "DELETE FROM reservations WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function createReservation($user_id, $table_id, $reservation_date, $guests)
    {
        $stmt = $this->conn->prepare("INSERT INTO reservations (user_id, table_id, reservation_date, guests) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $user_id, $table_id, $reservation_date, $guests);
        return $stmt->execute() ? $this->conn->insert_id : false;
    }

    public function isTableAvailable($table_id, $reservation_date)
    {
        $stmt = $this->conn->prepare("SELECT * FROM reservations WHERE table_id = ? AND reservation_date = ?");
        $stmt->bind_param("is", $table_id, $reservation_date);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows === 0;
    }

    public function getAllTables()
    {
        $result = $this->conn->query("SELECT * FROM tables");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
