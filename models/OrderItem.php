<?php
// modules/OrderItem.php

require_once '../conf/conf.php';

class OrderItem {
    public $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }
    // Add order items
    public function addOrderItems($order_id, $items) {
        foreach ($items as $item) {
            $dish_id = $item['dish_id'];
            $quantity = $item['quantity'];
            $price_at_order = $item['dish_price'];
            $sql = "INSERT INTO order_items (order_id, item_id, quantity, price_at_order) VALUES (?, ?, ?, ?)";
            $stmt =  $this->conn->prepare($sql);
            $stmt->bind_param("iiid", $order_id, $dish_id, $quantity, $price_at_order);
            $stmt->execute();
            $stmt->close();
        }
    }
}
?>