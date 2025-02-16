<?php
// modules/Order.php

require_once '../conf/conf.php';

class Order {
    public $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Create a new order
    public function createOrder($user_id, $total_price, $address, $payment_method) {
        $sql = "INSERT INTO orders (user_id, total_price, address, payment_method, payment_status, status) VALUES (?,?,?, ?, 'pending', 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("idss", $user_id, $total_price, $address, $payment_method);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly created order
        $stmt->close();
        return $order_id;
    }
  
}
?>