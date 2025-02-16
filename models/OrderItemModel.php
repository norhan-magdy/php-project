<?php

require_once '../conf/conf.php';


class OrderModel
{
    public $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Create a new order
    public function createOrder($user_id, $total_price, $address, $payment_method)
    {
        $sql = "INSERT INTO orders (user_id, total_price, address, payment_method, payment_status, status) VALUES (?,?,?, ?, 'pending', 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idss", $user_id, $total_price, $address, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly created order
        $stmt->close();
        return $order_id;
    }
    // ... existing code ...

    public function getAllOrders()
    {
        $sql = "SELECT * FROM orders ORDER BY order_date DESC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $sql = "UPDATE orders SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("si", $status, $orderId);
        return $stmt->execute();
    }

    public function deleteOrder($orderId)
    {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }
}
