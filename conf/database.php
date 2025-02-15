<?php

class Database {
    private $connect;

    // Constructor to establish the database connection
    public function __construct() {
        require_once 'conf.php'; // Load configuration
        $this->connect = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        if (!$this->connect) {
            throw new Exception("Connection failed: " . mysqli_connect_error());
        }
    }

    // Method to insert data into a table using prepared statements
    public function insert($table, $data) {
        $columns = array_keys($data);
        $values = array_values($data);

        $columnsStr = implode(", ", $columns);
        $placeholders = rtrim(str_repeat("?, ", count($columns)), ", ");

        $sql = "INSERT INTO $table ($columnsStr) VALUES ($placeholders)";
        $stmt = $this->prepareStatement($sql, $values, str_repeat("s", count($columns)));
        return $stmt->affected_rows > 0;
    }

    // Method to select data from a table using prepared statements
    public function select($table, $columns = "*", $conditions = []) {
        $sql = "SELECT $columns FROM $table";

        if (!empty($conditions)) {
            $whereClause = [];
            $values = [];
            foreach ($conditions as $column => $value) {
                $whereClause[] = "$column = ?";
                $values[] = $value;
            }
            $sql .= " WHERE " . implode(" AND ", $whereClause);
        }

        $stmt = $this->prepareStatement($sql, $values, str_repeat("s", count($values)));
        $result = $stmt->get_result();
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    // Method to update data in a table using prepared statements
    public function update($table, $data, $conditions) {
        $setClause = [];
        $setValues = [];
        foreach ($data as $column => $value) {
            $setClause[] = "$column = ?";
            $setValues[] = $value;
        }

        $whereClause = [];
        $whereValues = [];
        foreach ($conditions as $column => $value) {
            $whereClause[] = "$column = ?";
            $whereValues[] = $value;
        }

        $sql = "UPDATE $table SET " . implode(", ", $setClause) . " WHERE " . implode(" AND ", $whereClause);
        $stmt = $this->prepareStatement($sql, array_merge($setValues, $whereValues), str_repeat("s", count($setValues) + count($whereValues)));
        return $stmt->affected_rows > 0;
    }

    // Method to delete data from a table using prepared statements
    public function delete($table, $conditions) {
        $whereClause = [];
        $values = [];
        foreach ($conditions as $column => $value) {
            $whereClause[] = "$column = ?";
            $values[] = $value;
        }

        $sql = "DELETE FROM $table WHERE " . implode(" AND ", $whereClause);
        $stmt = $this->prepareStatement($sql, $values, str_repeat("s", count($values)));
        return $stmt->affected_rows > 0;
    }

    // Helper method to prepare and execute statements
    private function prepareStatement($sql, $params = [], $types = "") {
        $stmt = $this->connect->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Error preparing statement: " . $this->connect->error);
        }

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        return $stmt;
    }

    // Method to close the database connection
    public function close() {
        if ($this->connect) {
            $this->connect->close();
        }
    }
}

// Example usage:
try {
    $db = new Database();

    // Insert a new dish
    $db->insert('menu_items', [
        'name' => 'New Dish',
        'description' => 'A delicious new dish.',
        'price' => 9.99,
        'category_id' => 1,
        'image' => 'path/to/image.jpg',
        'availability' => 1
    ]);

    // Select dishes
    $dishes = $db->select('menu_items', '*', ['category_id' => 1]);
    print_r($dishes);

    // Update a dish
    $db->update('menu_items', ['price' => 10.99], ['id' => 1]);

    // Delete a dish
    $db->delete('menu_items', ['id' => 1]);

    // Close connection
    $db->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>