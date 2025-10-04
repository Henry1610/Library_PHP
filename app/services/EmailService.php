<?php
require_once __DIR__ . '/../config/email.php';

// Kiểm tra và load PHPMailer
if (file_exists(__DIR__ . '/../../vendor/autoload.php')) {
    require_once __DIR__ . '/../../vendor/autoload.php';
} elseif (file_exists(__DIR__ . '/../../PHPMailer/PHPMailer.php')) {
    require_once __DIR__ . '/../../PHPMailer/PHPMailer.php';
    require_once __DIR__ . '/../../PHPMailer/SMTP.php';
    require_once __DIR__ . '/../../PHPMailer/Exception.php';
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $config;
    
    public function __construct() {
        $this->config = new EmailConfig();
    }
    
    public function sendResetPasswordEmail($email, $token, $userName) {
        $subject = "Đặt lại mật khẩu - Thư viện CD_PHP";
        $resetLink = EmailConfig::$reset_url . $token;
        
        $message = "
        <html>
        <head>
            <title>Đặt lại mật khẩu</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: linear-gradient(90deg,#36d1c4,#007bff); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                    <h1 style='margin: 0;'>Thư viện CD_PHP</h1>
                </div>
                
                <div style='background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;'>
                    <h2 style='color: #007bff; margin-top: 0;'>Xin chào {$userName}!</h2>
                    
                    <p>Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>
                    
                    <p>Vui lòng nhấp vào nút bên dưới để đặt lại mật khẩu:</p>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$resetLink}' style='background: linear-gradient(90deg,#36d1c4,#007bff); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold;'>
                            Đặt lại mật khẩu
                        </a>
                    </div>
                    
                    <p>Hoặc copy link này vào trình duyệt:</p>
                    <p style='background: #e9ecef; padding: 10px; border-radius: 5px; word-break: break-all;'>
                        <a href='{$resetLink}' style='color: #007bff;'>{$resetLink}</a>
                    </p>
                    
                    <p><strong>Lưu ý:</strong></p>
                    <ul>
                        <li>Link này chỉ có hiệu lực trong 1 giờ</li>
                        <li>Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này</li>
                        <li>Để bảo mật, vui lòng không chia sẻ link này với người khác</li>
                    </ul>
                    
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                    
                    <p style='color: #666; font-size: 14px;'>
                        Email này được gửi tự động, vui lòng không trả lời.<br>
                        Nếu có vấn đề, vui lòng liên hệ admin.
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendEmail($email, $subject, $message);
    }
    
    public function sendWelcomeEmail($email, $userName) {
        $subject = "Chào mừng bạn đến với Thư viện CD_PHP!";
        $loginUrl = 'http://localhost/CD_PHP/index.php?action=login';
        
        $message = "
        <html>
        <head>
            <title>Chào mừng bạn</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: linear-gradient(90deg,#28a745,#20c997); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                    <h1 style='margin: 0;'>🎉 Chào mừng bạn!</h1>
                </div>
                
                <div style='background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;'>
                    <h2 style='color: #28a745; margin-top: 0;'>Xin chào {$userName}!</h2>
                    
                    <p>Cảm ơn bạn đã đăng ký tài khoản tại <strong>Thư viện CD_PHP</strong>!</p>
                    
                    <p>Tài khoản của bạn đã được tạo thành công và sẵn sàng sử dụng.</p>
                    
                    <div style='background: #e8f5e8; border-left: 4px solid #28a745; padding: 15px; margin: 20px 0;'>
                        <h3 style='color: #28a745; margin-top: 0;'>🎯 Những gì bạn có thể làm:</h3>
                        <ul style='margin: 10px 0;'>
                            <li>📚 Tìm kiếm và mượn sách từ kho tàng phong phú</li>
                            <li>📖 Xem chi tiết sách và đánh giá</li>
                            <li>📅 Quản lý lịch sử mượn/trả sách</li>
                            <li>💳 Thanh toán online qua VNPay</li>
                            <li>📧 Nhận thông báo về sách mới</li>
                        </ul>
                    </div>
                    
                    <div style='text-align: center; margin: 30px 0;'>
                        <a href='{$loginUrl}' style='background: linear-gradient(90deg,#28a745,#20c997); color: white; padding: 15px 30px; text-decoration: none; border-radius: 25px; display: inline-block; font-weight: bold;'>
                            🚀 Bắt đầu ngay
                        </a>
                    </div>
                    
                    <div style='background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 5px; padding: 15px; margin: 20px 0;'>
                        <h4 style='color: #856404; margin-top: 0;'>💡 Mẹo sử dụng:</h4>
                        <ul style='margin: 10px 0; color: #856404;'>
                            <li>Sử dụng bộ lọc để tìm sách theo danh mục, giá, tác giả</li>
                            <li>Thêm sách vào giỏ mượn để mượn nhiều cuốn cùng lúc</li>
                            <li>Kiểm tra lịch sử mượn sách để theo dõi thời hạn trả</li>
                            <li>Liên hệ admin nếu cần hỗ trợ</li>
                        </ul>
                    </div>
                    
                    <hr style='border: none; border-top: 1px solid #ddd; margin: 30px 0;'>
                    
                    <p style='color: #666; font-size: 14px;'>
                        Chúc bạn có những trải nghiệm tuyệt vời với thư viện của chúng tôi!<br>
                        Nếu có câu hỏi, đừng ngần ngại liên hệ với chúng tôi.
                    </p>
                    
                    <p style='color: #666; font-size: 14px;'>
                        Trân trọng,<br>
                        <strong>Đội ngũ Thư viện CD_PHP</strong>
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->sendEmail($email, $subject, $message);
    }
    
    public function sendOtpEmail($email, $otp, $userName) {
        $subject = "Mã xác thực đăng ký (OTP) - Thư viện CD_PHP";
        $message = "
        <html>
        <head>
            <title>Mã OTP đăng ký</title>
        </head>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
            <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                <div style='background: linear-gradient(90deg,#36d1c4,#007bff); color: white; padding: 20px; text-align: center; border-radius: 10px 10px 0 0;'>
                    <h1 style='margin: 0;'>Xác thực tài khoản</h1>
                </div>
                <div style='background: #f9f9f9; padding: 30px; border-radius: 0 0 10px 10px;'>
                    <p>Xin chào {$userName},</p>
                    <p>Mã xác thực OTP của bạn là:</p>
                    <div style='text-align:center; font-size: 28px; font-weight: bold; letter-spacing: 6px; padding: 12px 0;'>
                        {$otp}
                    </div>
                    <p>Mã có hiệu lực trong 5 phút. Vui lòng không chia sẻ mã này với bất kỳ ai.</p>
                </div>
            </div>
        </body>
        </html>
        ";
        return $this->sendEmail($email, $subject, $message);
    }
    
    public function sendBorrowSuccessEmail($user, $details, $books) {
        $bookList = '';
        foreach ($details as $item) {
            $book = $books[$item['book_id']] ?? null;
            if ($book) {
                $bookList .= '<li><b>' . htmlspecialchars($book['title']) . '</b> (Tác giả: ' . htmlspecialchars($book['author']) . ')<br>Số lượng: ' . (int)$item['quantity'] . ', Từ: ' . htmlspecialchars($item['borrow_date']) . ' đến: ' . htmlspecialchars($item['return_date']) . '</li>';
            }
        }
        $subject = 'Xác nhận mượn sách thành công';
        $message = '<h2>Chào ' . htmlspecialchars($user['name']) . '!</h2>';
        $message .= '<p>Bạn đã mượn sách thành công qua hệ thống thư viện. Dưới đây là thông tin chi tiết:</p>';
        $message .= '<ul>' . $bookList . '</ul>';
        $message .= '<p>Vui lòng đến thư viện để nhận sách đúng thời gian quy định.</p>';
        return $this->sendEmail($user['email'], $subject, $message);
    }
    
    private function sendEmail($to, $subject, $message) {
        try {
            $mail = new PHPMailer(true);
            
            // Cấu hình SMTP
            $mail->isSMTP();
            $mail->Host = EmailConfig::$smtp_host;
            $mail->SMTPAuth = true;
            $mail->Username = EmailConfig::$smtp_username;
            $mail->Password = EmailConfig::$smtp_password;
            $mail->SMTPSecure = EmailConfig::$smtp_secure;
            $mail->Port = EmailConfig::$smtp_port;
            $mail->CharSet = 'UTF-8';
            
            // Debug (tắt trong production)
            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            
            // Người gửi và người nhận
            $mail->setFrom(EmailConfig::$from_email, EmailConfig::$from_name);
            $mail->addAddress($to);
            
            // Nội dung
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $message;
            
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            return false;
        }
    }
}
?> 