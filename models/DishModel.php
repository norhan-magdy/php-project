<?php
// models/DishModel.php
require_once '../conf/conf.php';

class DishModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }



    // Get all dishes
    public function getAllDishes()
    {
        $sql = "SELECT * FROM menu_items";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get a dish by ID
    public function getDishById($id)
    {
        $sql = "SELECT * FROM menu_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Change from individual parameters to array

    public function addDish($dishData)
    {
        $sql = "INSERT INTO menu_items (name, description, price, category_id, availability, image) 
            VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $dishData['name'],
            $dishData['description'],
            $dishData['price'],
            $dishData['category_id'],
            $dishData['availability'],
            $dishData['image']
        ]);
    }

    // Update updateDish similarly
    public function updateDish($dishData)
    {
        $sql = "UPDATE menu_items SET 
            name = ?,
            description = ?,
            price = ?,
            category_id = ?,
            availability = ?,
            image = ?
            WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $dishData['name'],
            $dishData['description'],
            $dishData['price'],
            $dishData['category_id'],
            $dishData['availability'],
            $dishData['image'],
            $dishData['id']
        ]);
    }

    // Delete a dish
    public function deleteDish($id)
    {
        $sql = "DELETE FROM menu_items WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    // Get dishes by category ID
    public function getDishesByCategory($category_id)
    {
        $sql = "SELECT * FROM menu_items WHERE category_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $category_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAveragePrice()
    {
        $result = $this->conn->query("SELECT AVG(price) AS avg_price FROM menu_items");
        $row = $result->fetch_assoc();
        return (float)$row['avg_price'];
    }

    public function countAvailableItems()
    {
        $result = $this->conn->query("SELECT COUNT(*) AS item_count FROM menu_items WHERE availability = 1");
        $row = $result->fetch_assoc();
        return (int)$row['item_count'];
    }
}
