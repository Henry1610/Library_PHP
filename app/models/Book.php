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
        $stmt = $this->conn->prepare("INSERT INTO $this->table (title, author, publisher, year, category_id, isbn, cover_img, quantity, available, price, borrow_count) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $borrow_count = $data['borrow_count'] ?? 0;
        $stmt->bind_param('ssssssssidi', $data['title'], $data['author'], $data['publisher'], $data['year'], $data['category_id'], $data['isbn'], $data['cover_img'], $data['quantity'], $data['available'], $data['price'], $borrow_count);
        return $stmt->execute();
    }
    public function update($id, $data) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET title=?, author=?, publisher=?, year=?, category_id=?, isbn=?, cover_img=?, quantity=?, available=?, price=?, borrow_count=? WHERE id=?");
        $borrow_count = $data['borrow_count'] ?? 0;
        $stmt->bind_param('ssssssssidii', $data['title'], $data['author'], $data['publisher'], $data['year'], $data['category_id'], $data['isbn'], $data['cover_img'], $data['quantity'], $data['available'], $data['price'], $borrow_count, $id);
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
    
    // Thêm phương thức để tăng lượt mượn
    public function incrementBorrowCount($book_id, $quantity = 1) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET borrow_count = borrow_count + ? WHERE id = ?");
        $stmt->bind_param('ii', $quantity, $book_id);
        return $stmt->execute();
    }
    
    // Thêm phương thức để giảm số lượng có sẵn
    public function updateAvailable($book_id, $quantity = 1) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET available = available - ? WHERE id = ? AND available >= ?");
        $stmt->bind_param('iii', $quantity, $book_id, $quantity);
        return $stmt->execute();
    }
    
    // Thêm phương thức để tăng lại số lượng có sẵn khi trả sách
    public function returnAvailable($book_id, $quantity = 1) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET available = available + ? WHERE id = ?");
        $stmt->bind_param('ii', $quantity, $book_id);
        return $stmt->execute();
    }
    
    // Lấy sách được mượn nhiều nhất
    public function getMostBorrowed($limit = 10) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY borrow_count DESC LIMIT ?");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Lấy sách mới nhất
    public function getLatest($limit = 10) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY id DESC LIMIT ?");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Tìm kiếm sách
    public function search($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE title LIKE ? OR author LIKE ? OR isbn LIKE ? ORDER BY borrow_count DESC");
        $stmt->bind_param('sss', $keyword, $keyword, $keyword);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function countAll() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM $this->table");
        $row = $result->fetch_assoc();
        return $row['total'];
    }

    // Lấy sách có số lượng ít nhất
    public function getLowestQuantity($limit = 3) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY quantity ASC LIMIT ?");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} 