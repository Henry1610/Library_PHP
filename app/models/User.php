<?php
require_once __DIR__ . '/../config/database.php';
class User {
    private $conn;
    private $table = 'users';

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function register($name, $email, $password, $phone, $address, $role = 'user') {
        $stmt = $this->conn->prepare("INSERT INTO $this->table (name, email, password, phone, address, role) VALUES (?, ?, ?, ?, ?, ?)");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('ssssss', $name, $email, $hashedPassword, $phone, $address, $role);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = ? LIMIT 1");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAll() {
        $stmt = $this->conn->prepare("SELECT * FROM $this->table ORDER BY id DESC");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateRole($id, $role) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET role = ? WHERE id = ?");
        $stmt->bind_param('si', $role, $id);
        return $stmt->execute();
    }

    public function updatePassword($email, $password) {
        $stmt = $this->conn->prepare("UPDATE $this->table SET password = ? WHERE email = ?");
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt->bind_param('ss', $hashedPassword, $email);
        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function countAll() {
        $result = $this->conn->query("SELECT COUNT(*) as total FROM $this->table");
        $row = $result->fetch_assoc();
        return $row['total'];
    }
} 