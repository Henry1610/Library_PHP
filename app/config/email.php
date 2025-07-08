<?php
class EmailConfig {
    // Cấu hình SMTP Gmail
    public static $smtp_host = 'smtp.gmail.com';
    public static $smtp_port = 587;
    public static $smtp_username = 'trannguyentruong6@gmail.com';
    public static $smtp_password = 'znah ihad opyg mzte';
    public static $smtp_secure = 'tls';
    
    // Thông tin người gửi
    public static $from_email = 'trannguyentruong6@gmail.com';
    public static $from_name = 'Thư viện CD_PHP';
    
    // URL reset password
    public static $reset_url = 'http://localhost/CD_PHP/index.php?action=showResetPassword&token=';
}
?> 