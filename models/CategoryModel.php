<?php
// models/CategoryModel.php
require_once '../conf/conf.php';

class CategoryModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Get all categories
    public function getAllCategories() {
        $sql = "SELECT * FROM menu_categories";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add a new category
    public function addCategory($name, $status = 'active') {
        $sql = "INSERT INTO menu_categories (name, status) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $name, $status);
        return $stmt->execute();
    }

    // Update a category
    public function updateCategory($id, $name, $status) {
        $sql = "UPDATE menu_categories SET name = ?, status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssi', $name, $status, $id);
        return $stmt->execute();
    }

    // Delete a category
    public function deleteCategory($id) {
        $sql = "DELETE FROM menu_categories WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

   

}
?>