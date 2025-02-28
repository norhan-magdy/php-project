<?php

require_once '../conf/conf.php';

class  OrderModel
{

    public $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    // Create a new order
    public function createOrder($user_id, $total_price, $address,$phone, $payment_method)
    {
        $sql = "INSERT INTO orders (user_id, total_price, address,phone, payment_method, payment_status, status) VALUES (?,?,?,?, ?, 'pending', 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idsss", $user_id, $total_price, $address,$phone, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly created order
        $stmt->close();
        return $order_id;
    }

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

    public function getOrderDetails($order_id)
    {
        $sql = "SELECT * FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function deleteOrder($orderId)
    {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
    }

    public function getOrdersByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
}
}
