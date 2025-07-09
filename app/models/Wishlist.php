<?php
require_once __DIR__ . '/../config/database.php';

class Wishlist {
    private $conn;
    private $table = 'wishlists';
    
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    
    // Thêm sách vào wishlist
    public function addToWishlist($user_id, $book_id) {
        // Kiểm tra xem đã có trong wishlist chưa
        if ($this->isInWishlist($user_id, $book_id)) {
            return false; // Đã có rồi
        }
        
        $stmt = $this->conn->prepare("INSERT INTO $this->table (user_id, book_id, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('ii', $user_id, $book_id);
        return $stmt->execute();
    }
    
    // Xóa sách khỏi wishlist
    public function removeFromWishlist($user_id, $book_id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param('ii', $user_id, $book_id);
        return $stmt->execute();
    }
    
    // Kiểm tra sách có trong wishlist không
    public function isInWishlist($user_id, $book_id) {
        $stmt = $this->conn->prepare("SELECT 1 FROM $this->table WHERE user_id = ? AND book_id = ?");
        $stmt->bind_param('ii', $user_id, $book_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }
    
    // Lấy danh sách wishlist của user
    public function getUserWishlist($user_id) {
        $stmt = $this->conn->prepare("
            SELECT w.*, b.title, b.author, b.publisher, b.year, b.isbn, b.cover_img, b.price, b.available, b.borrow_count, c.name as category_name
            FROM $this->table w
            JOIN books b ON w.book_id = b.id
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE w.user_id = ?
            ORDER BY w.created_at DESC
        ");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    // Đếm số sách trong wishlist
    public function getWishlistCount($user_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM $this->table WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'];
    }
    
    // Xóa tất cả wishlist của user
    public function clearWishlist($user_id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE user_id = ?");
        $stmt->bind_param('i', $user_id);
        return $stmt->execute();
    }
} 