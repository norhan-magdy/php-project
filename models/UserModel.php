<?php
// models/UserModel.php
require_once '../conf/conf.php';

class UserModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    // Register a new user
   

    // Login a user
    public function loginUser($username, $password) {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    // Get user by ID
    public function getUserById($id) {
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Update user profile
    public function updateUser($id, $username, $email, $phone, $profile_picture) {
        $sql = "UPDATE users SET username = ?, email = ?, phone = ?, profile_picture = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssssi', $username, $email, $phone, $profile_picture, $id);
        return $stmt->execute();
    }

    // Delete a user
    public function deleteUser($id) {
        $sql = "DELETE FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function registerUser($username, $password, $email, $role = 'customer') {
        $sql = "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ssss', $username, $password, $email, $role);
        $stmt->execute();
        return $stmt->insert_id;
    }



    // Check if username or email already exists
    public function getUserByUsernameOrEmail($username, $email) {
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
}
?>