<?php
require_once __DIR__ . '/../config/database.php';
class Borrowing {
    private $conn;
    private $table = 'borrowings';
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }
    public function add($user_id, $status = 'borrowed', $note = '') {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (user_id, status, note) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $user_id, $status, $note);
        $stmt->execute();
        return $this->conn->insert_id;
    }
} 