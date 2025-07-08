<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../services/EmailService.php';
// session_start();

class AuthController {
    public function showLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }
    public function showRegister() {
        require __DIR__ . '/../views/auth/register.php';
    }
    public function showForgotPassword() {
        require __DIR__ . '/../views/auth/forgot_password.php';
    }
    public function showResetPassword() {
        $token = $_GET['token'] ?? '';
        if (empty($token)) {
            header('Location: index.php?action=login');
            exit;
        }
        require __DIR__ . '/../views/auth/reset_password.php';
    }
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $userModel = new User();
            $user = $userModel->login($email, $password);
            if ($user) {
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Email hoặc mật khẩu không đúng!';
                require __DIR__ . '/../views/auth/login.php';
            }
        }
    }
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $address = $_POST['address'] ?? '';
            $userModel = new User();
            if ($userModel->getByEmail($email)) {
                $error = 'Email đã tồn tại!';
                require __DIR__ . '/../views/auth/register.php';
                return;
            }
            $success = $userModel->register($name, $email, $password, $phone, $address);
            if ($success) {
                // Gửi email chào mừng
                $emailService = new EmailService();
                $emailSent = $emailService->sendWelcomeEmail($email, $name);
                
                // Hiển thị thông báo thành công
                $success_message = 'Đăng ký thành công! Vui lòng kiểm tra email để xem thông tin chào mừng.';
                require __DIR__ . '/../views/auth/login.php';
            } else {
                $error = 'Đăng ký thất bại!';
                require __DIR__ . '/../views/auth/register.php';
            }
        }
    }
    public function forgotPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            
            if (empty($email)) {
                $error = 'Vui lòng nhập email!';
                require __DIR__ . '/../views/auth/forgot_password.php';
                return;
            }
            
            $userModel = new User();
            $user = $userModel->getByEmail($email);
            
            if (!$user) {
                $error = 'Email không tồn tại trong hệ thống!';
                require __DIR__ . '/../views/auth/forgot_password.php';
                return;
            }
            
            // Tạo token reset password
            $passwordResetModel = new PasswordReset();
            $token = $passwordResetModel->createResetToken($email);
            
            if ($token) {
                // Gửi email
                $emailService = new EmailService();
                $emailSent = $emailService->sendResetPasswordEmail($email, $token, $user['name']);
                
                if ($emailSent) {
                    $success = 'Email đặt lại mật khẩu đã được gửi! Vui lòng kiểm tra hộp thư của bạn.';
                } else {
                    $error = 'Không thể gửi email. Vui lòng thử lại sau!';
                }
            } else {
                $error = 'Có lỗi xảy ra. Vui lòng thử lại sau!';
            }
            
            require __DIR__ . '/../views/auth/forgot_password.php';
        }
    }
    public function resetPassword() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            
            if (empty($token) || empty($password) || empty($confirm_password)) {
                $error = 'Vui lòng điền đầy đủ thông tin!';
                require __DIR__ . '/../views/auth/reset_password.php';
                return;
            }
            
            if ($password !== $confirm_password) {
                $error = 'Mật khẩu xác nhận không khớp!';
                require __DIR__ . '/../views/auth/reset_password.php';
                return;
            }
            
            if (strlen($password) < 6) {
                $error = 'Mật khẩu phải có ít nhất 6 ký tự!';
                require __DIR__ . '/../views/auth/reset_password.php';
                return;
            }
            
            // Validate token
            $passwordResetModel = new PasswordReset();
            $email = $passwordResetModel->validateToken($token);
            
            if (!$email) {
                $error = 'Token không hợp lệ hoặc đã hết hạn!';
                require __DIR__ . '/../views/auth/reset_password.php';
                return;
            }
            
            // Cập nhật mật khẩu
            $userModel = new User();
            $success = $userModel->updatePassword($email, $password);
            
            if ($success) {
                // Đánh dấu token đã sử dụng
                $passwordResetModel->markTokenAsUsed($token);
                $success_message = 'Mật khẩu đã được đặt lại thành công! Bạn có thể đăng nhập với mật khẩu mới.';
                require __DIR__ . '/../views/auth/login.php';
            } else {
                $error = 'Có lỗi xảy ra khi đặt lại mật khẩu!';
                require __DIR__ . '/../views/auth/reset_password.php';
            }
        }
    }
    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
} 