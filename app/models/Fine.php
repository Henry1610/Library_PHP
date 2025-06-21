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
        $stmt = $this->conn->prepare("INSERT INTO $this->table (borrowing_id, amount) VALUES (?, ?)");
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
} 