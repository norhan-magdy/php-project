<?php

require_once '../conf/conf.php';

class OrderItemModel
{

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

    public $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }
    public function getItemsByOrderId($orderId)
    {
        $sql = "SELECT oi.*, mi.name 
                    FROM order_items oi
                    JOIN menu_items mi ON oi.item_id = mi.id
                    WHERE order_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
