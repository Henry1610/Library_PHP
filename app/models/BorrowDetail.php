<?php
require_once __DIR__ . '/../config/database.php';
class BorrowDetail {
    private $conn;
    private $table = 'borrow_details';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function add($borrowing_id, $book_id, $quantity, $borrow_date, $return_date) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (borrowing_id, book_id, quantity, borrow_date, return_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param('iiiss', $borrowing_id, $book_id, $quantity, $borrow_date, $return_date);
        return $stmt->execute();
    }
    // Lấy các borrow_details chưa xác nhận mượn (giả sử borrowing_id là NULL hoặc có trạng thái 'cart')
    public function getCurrentCartByUser($userId) {
        // Giả sử có trạng thái 'cart' trong borrowings, nếu không thì lấy các borrowings chưa có status 'borrowed' hoặc 'returned'
        $sql = "SELECT bd.* FROM borrow_details bd
                JOIN borrowings b ON bd.borrowing_id = b.id
                WHERE b.user_id = ? AND b.status = 'cart'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
} 