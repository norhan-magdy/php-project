<?php
// models/DishModel.php
require_once '../conf/conf.php';

class DishModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Get all dishes
    public function getAllDishes() {
        $sql = "SELECT * FROM menu_items";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get a dish by ID
    public function getDishById($id) {
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Add a new dish
    public function addDish($name, $description, $price, $image, $category_id, $availability) {
        $sql = "INSERT INTO menu_items (name, description, price, image, category_id, availability) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssdsii', $name, $description, $price, $image, $category_id, $availability);
        return $stmt->execute();
    }

    // Update a dish
    public function updateDish($id, $name, $description, $price, $image, $category_id, $availability) {
        $sql = "UPDATE menu_items SET name = ?, description = ?, price = ?, image = ?, category_id = ?, availability = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssdsiii', $name, $description, $price, $image, $category_id, $availability, $id);
        return $stmt->execute();
    }

    // Delete a dish
    public function deleteDish($id) {
        $sql = "DELETE FROM menu_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
   

    // Get dishes by category ID
    public function getDishesByCategory($category_id) {
        $sql = "SELECT * FROM menu_items WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>