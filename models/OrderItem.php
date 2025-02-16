<?php
// modules/OrderItem.php

require_once '../conf/conf.php';

class OrderItem {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Add order items
    public function addOrderItems($order_id, $items) {
        $conn = $this->db->connect();
        foreach ($items as $item) {
            $dish_id = $item['dish_id'];
            $quantity = $item['quantity'];
            $price_at_order = $item['dish_price'];

            $sql = "INSERT INTO order_items (order_id, item_id, quantity, price_at_order) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $order_id, $dish_id, $quantity, $price_at_order);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>