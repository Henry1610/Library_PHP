<?php
require_once __DIR__ . '/env.php';

class EmailConfig {
    // Cấu hình SMTP (có thể override qua biến môi trường)
    public static $smtp_host = null;
    public static $smtp_port = null;
    public static $smtp_username = null;
    public static $smtp_password = null;
    public static $smtp_secure = null;
    
    // Thông tin người gửi
    public static $from_email = null;
    public static $from_name = 'Thư viện CD_PHP';
    
    // URL reset password (đọc từ BASE/RESET_URL_BASE nếu có)
    public static $reset_url = null;
}

// Khởi tạo giá trị mặc định và lấy từ env nếu có
EmailConfig::$smtp_host = $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com';
EmailConfig::$smtp_port = isset($_ENV['SMTP_PORT']) ? (int)$_ENV['SMTP_PORT'] : 587;
EmailConfig::$smtp_username = $_ENV['SMTP_USERNAME'] ?? 'trannguyentruong6@gmail.com';
EmailConfig::$smtp_password = $_ENV['SMTP_PASSWORD'] ?? 'znah ihad opyg mzte';
EmailConfig::$smtp_secure = $_ENV['SMTP_SECURE'] ?? 'tls';

EmailConfig::$from_email = $_ENV['FROM_EMAIL'] ?? 'trannguyentruong6@gmail.com';
EmailConfig::$from_name = $_ENV['FROM_NAME'] ?? EmailConfig::$from_name;

$resetBase = rtrim($_ENV['RESET_URL_BASE'] ?? $_ENV['BASE_URL'] ?? 'http://localhost/CD_PHP', '/');
EmailConfig::$reset_url = $resetBase . '/index.php?action=showResetPassword&token=';

?>