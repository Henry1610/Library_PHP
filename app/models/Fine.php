<?php
require_once __DIR__ . '/../config/database.php';
class Fine {
    private $conn;
    private $table = 'fines';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function create($borrowing_id, $amount) {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (borrowing_id, amount, created_at) VALUES (?, ?, NOW())");
        $stmt->bind_param('id', $borrowing_id, $amount);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    public function getByBorrowingId($borrowing_id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE borrowing_id = ?");
        $stmt->bind_param('i', $borrowing_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function updateAmount($id, $amount) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET amount = ? WHERE id = ?");
        $stmt->bind_param('di', $amount, $id);
        return $stmt->execute();
    }

    public function getByUserIdUnpaid($user_id) {
        $stmt = $this->conn->prepare(
            "SELECT f.* FROM fines f
            JOIN borrowings b ON f.borrowing_id = b.id
            LEFT JOIN transactions t ON f.id = t.fine_id
            WHERE b.user_id = ? AND (t.status IS NULL OR t.status != 'success')"
        );
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} 