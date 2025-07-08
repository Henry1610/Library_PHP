<?php
require_once __DIR__ . '/../config/database.php';

class PasswordReset {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->connect();
    }
    
    public function createResetToken($email) {
        // Tạo token ngẫu nhiên
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Xóa token cũ nếu có
        $this->deleteOldTokens($email);
        
        // Lưu token mới
        $query = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("sss", $email, $token, $expires);
        
        if ($stmt->execute()) {
            return $token;
        }
        return false;
    }
    
    public function validateToken($token) {
        $query = "SELECT email, expires_at FROM password_resets WHERE token = ? AND used = 0";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $expires = new DateTime($row['expires_at']);
            $now = new DateTime();
            
            if ($now < $expires) {
                return $row['email'];
            }
        }
        return false;
    }
    
    public function markTokenAsUsed($token) {
        $query = "UPDATE password_resets SET used = 1 WHERE token = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $token);
        return $stmt->execute();
    }
    
    private function deleteOldTokens($email) {
        $query = "DELETE FROM password_resets WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
    }
    
    public function cleanupExpiredTokens() {
        $query = "DELETE FROM password_resets WHERE expires_at < NOW() OR used = 1";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute();
    }
}
?> 