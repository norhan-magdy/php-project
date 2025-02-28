<?php

require_once '../conf/conf.php';


class OrderItemModel
{
    public $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
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

    public function deleteOrder($orderId)
    {
        $sql = "DELETE FROM orders WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $orderId);
        return $stmt->execute();
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
    public function addOrderItems($order_id, $items)
    {
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
