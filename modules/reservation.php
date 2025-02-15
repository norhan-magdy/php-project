<?php
// models/ReservationModel.php
include_once '../conf/conf.php';
class ReservationModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Create a new reservation
    public function createReservation($user_id, $table_id, $reservation_date, $guests) {
        $sql = "INSERT INTO reservations (user_id, table_id, reservation_date, guests) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('iisi', $user_id, $table_id, $reservation_date, $guests);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Get all reservations
    public function getAllReservations() {
        $sql = "SELECT * FROM reservations";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get reservations by user ID
    public function getReservationsByUserId($user_id) {
        $sql = "SELECT * FROM reservations WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update reservation status
    public function updateReservationStatus($id, $status) {
        $sql = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    // Delete a reservation
    public function deleteReservation($id) {
        $sql = "DELETE FROM reservations WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>