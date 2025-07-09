<?php
require_once __DIR__ . '/../config/database.php';
class Transaction {
    private $conn;
    private $table = 'transactions';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function create($user_id, $borrowing_id, $fine_id, $amount, $method = 'cash', $status = 'pending') {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (user_id, borrowing_id, fine_id, amount, method, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('iiidss', $user_id, $borrowing_id, $fine_id, $amount, $method, $status);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
    
    public function updateMethod($id, $method) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET method = ? WHERE id = ?");
        $stmt->bind_param('si', $method, $id);
        return $stmt->execute();
    }
    public function getByBorrowingId($borrowing_id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE borrowing_id = ?");
        $stmt->bind_param('i', $borrowing_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    public function getByFineId($fine_id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE fine_id = ?");
        $stmt->bind_param('i', $fine_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
    
    public function updateVnpayInfo($id, $vnpay_transaction_no, $vnpay_bank_code) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET vnpay_transaction_no = ?, vnpay_bank_code = ? WHERE id = ?");
        $stmt->bind_param('ssi', $vnpay_transaction_no, $vnpay_bank_code, $id);
        return $stmt->execute();
    }

    public function getTotalRevenue() {
        $stmt = $this->conn->prepare("SELECT SUM(amount) as total FROM $this->table WHERE status = 'success'");
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] ?? 0;
    }
} 