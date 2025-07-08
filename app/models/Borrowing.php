<?php
require_once __DIR__ . '/../config/database.php';
class Borrowing {
    private $conn;
    private $table = 'borrowings';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function add($user_id, $status = 'borrowed') {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (user_id, status) VALUES (?, ?)");
        $stmt->bind_param('is', $user_id, $status);
        $stmt->execute();
        return $this->conn->insert_id;
    }
    public function updateApprovalStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET approval_status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
    public function updateReturnApprovalStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET return_approval_status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
    public function updateStatus($id, $status) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET status = ? WHERE id = ?");
        $stmt->bind_param('si', $status, $id);
        return $stmt->execute();
    }
    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
} 