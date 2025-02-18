<?php

require_once '../conf/conf.php';
class InventoryModel
{
  private $conn;

  public function __construct()
  {
    global $conn;
    $this->conn = $conn;
  }

  public function addInventoryItem($data)
  {
    $sql = "INSERT INTO inventory (item_name, quantity, reorder_level, supplier_id) 
                VALUES (?, ?, ?, ?)";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
      'siii',
      $data['item_name'],
      $data['quantity'],
      $data['reorder_level'],
      $data['supplier_id']
    );
    return $stmt->execute();
  }

  public function updateInventoryItem($data)
  {
    $sql = "UPDATE inventory SET 
                item_name = ?,
                quantity = ?,
                reorder_level = ?,
                supplier_id = ?
                WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param(
      'siiii',
      $data['item_name'],
      $data['quantity'],
      $data['reorder_level'],
      $data['supplier_id'],
      $data['id']
    );
    return $stmt->execute();
  }

  public function deleteInventoryItem($id)
  {
    $sql = "DELETE FROM inventory WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $id);
    return $stmt->execute();
  }

  public function getAllInventoryItems()
  {
    $sql = "SELECT * FROM inventory";
    $result = $this->conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function getInventoryItemById($id)
  {
    $sql = "SELECT * FROM inventory WHERE id = ?";
    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
  }
}
