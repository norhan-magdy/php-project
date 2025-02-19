<?php
require_once '../conf/conf.php';


class SupplierModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    // Get all suppliers
    public function getAllSuppliers()
    {
        $sql = "SELECT * FROM suppliers";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add a new supplier
    public function addSupplier($name, $contact, $email)
    {
        $sql = "INSERT INTO suppliers (name, contact, email) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sss', $name, $contact, $email);
        return $stmt->execute();
    }

    // Update a supplier
    public function updateSupplier($id, $name, $contact, $email)
    {
        $sql = "UPDATE suppliers SET name = ?, contact = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('sssi', $name, $contact, $email, $id);
        return $stmt->execute();
    }

    // Delete a supplier
    public function deleteSupplier($id)
    {
        $sql = "DELETE FROM suppliers WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
}
