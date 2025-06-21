<?php
require_once __DIR__ . '/../config/database.php';
class Book {
    private $conn;
    private $table = 'books';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function getAll() {
        $result = $this->conn->query("SELECT * FROM $this->table");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function add($data) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (title, author, publisher, year, category_id, isbn, cover_img, quantity, available) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssssssi', $data['title'], $data['author'], $data['publisher'], $data['year'], $data['category_id'], $data['isbn'], $data['cover_img'], $data['quantity'], $data['available']);
        return $stmt->execute();
    }
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET title=?, author=?, publisher=?, year=?, category_id=?, isbn=?, cover_img=?, quantity=?, available=? WHERE id=?");
        $stmt->bind_param('ssssssssii', $data['title'], $data['author'], $data['publisher'], $data['year'], $data['category_id'], $data['isbn'], $data['cover_img'], $data['quantity'], $data['available'], $id);
        return $stmt->execute();
    }
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    public function updateQuantity($book_id, $quantity) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET quantity = quantity - ? WHERE id = ?");
        $stmt->bind_param('ii', $quantity, $book_id);
        return $stmt->execute();
    }
} 