<?php
// modules/Order.php

require_once '../conf/conf.php';

class Order {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Create a new order
    public function createOrder($user_id, $total_price) {
        $conn = $this->db->connect();
        $sql = "INSERT INTO orders (user_id, total_price, payment_status, status) VALUES (?, ?, 'pending', 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("id", $user_id, $total_price);
        $stmt->execute();
        $order_id = $stmt->insert_id; // Get the ID of the newly created order
        $stmt->close();
        return $order_id;
    }
}
?>