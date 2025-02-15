<?php
// models/OrderModel.php
require_once 'conf.php';

class OrderModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Create a new order
    public function createOrder($user_id, $total_price) {
        $sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('id', $user_id, $total_price);
        $stmt->execute();
        return $stmt->insert_id;
    }

    // Get all orders
    public function getAllOrders() {
        $sql = "SELECT * FROM orders";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get orders by user ID
    public function getOrdersByUserId($user_id) {
        $sql = "SELECT * FROM orders WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Update order status
    public function updateOrderStatus($id, $status) {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }

    // Delete an order
    public function deleteOrder($id) {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
?>