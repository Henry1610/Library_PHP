<?php
require_once __DIR__ . '/../config/database.php';
class Category {
    private $conn;
    private $table = 'categories';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function getAll() {
        $sql = "SELECT c.*, COUNT(b.id) as book_count FROM $this->table c LEFT JOIN books b ON c.id = b.category_id GROUP BY c.id ORDER BY c.id ASC";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function add($data) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (name, description) VALUES (?, ?)");
        $stmt->bind_param('ss', $data['name'], $data['description']);
        return $stmt->execute();
    }
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET name=?, description=? WHERE id=?");
        $stmt->bind_param('ssi', $data['name'], $data['description'], $id);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public function hasBooks($id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as total FROM books WHERE category_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }
    public function countAll() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM $this->table");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
} 