<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/PasswordReset.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/BorrowingController.php';
require_once __DIR__ . '/../services/EmailService.php';
// session_start();

class AuthController {
    private function generateOtp(): string {
        return str_pad(strval(random_int(0, 999999)), 6, '0', STR_PAD_LEFT);
    }

    public function showVerifyOtp() {
        if (empty($_SESSION['pending_registration'])) {
            header('Location: index.php?action=register');
            exit;
        }
        require __DIR__ . '/../views/auth/verify_otp.php';
    }

    public function resendOtp() {
        if (empty($_SESSION['pending_registration'])) {
            header('Location: index.php?action=register');
            exit;
        }
        $otp = $this->generateOtp();
        $_SESSION['registration_otp'] = [
            'code' => $otp,
            'expires_at' => time() + 300 // 5 phút
        ];
        $emailService = new EmailService();
        $emailService->sendOtpEmail($_SESSION['pending_registration']['email'], $otp, $_SESSION['pending_registration']['name']);
        $info = 'OTP mới đã được gửi đến email của bạn.';
        require __DIR__ . '/../views/auth/verify_otp.php';
    }

    public function verifyOtp() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?action=show_verify_otp');
            exit;
        }
        $otp = $_POST['otp'] ?? '';
        if (empty($_SESSION['pending_registration']) || empty($_SESSION['registration_otp'])) {
            $error = 'Phiên xác thực đã hết hạn. Vui lòng đăng ký lại.';
            require __DIR__ . '/../views/auth/register.php';
            return;
        }
        $sessionOtp = $_SESSION['registration_otp'];
        if (time() > ($sessionOtp['expires_at'] ?? 0)) {
            $error = 'OTP đã hết hạn. Vui lòng yêu cầu gửi lại.';
            require __DIR__ . '/../views/auth/verify_otp.php';
            return;
        }
        if ($otp !== ($sessionOtp['code'] ?? '')) {
            $error = 'OTP không đúng. Vui lòng thử lại.';
            require __DIR__ . '/../views/auth/verify_otp.php';
            return;
        }
        // OTP hợp lệ → tiến hành tạo tài khoản
        $data = $_SESSION['pending_registration'];
        $userModel = new User();
        $success = $userModel->register($data['name'], $data['email'], $data['password'], $data['phone'], $data['address']);
        if ($success) {
            // Gửi email chào mừng (không bắt buộc)
            $emailService = new EmailService();
            $emailService->sendWelcomeEmail($data['email'], $data['name']);
            unset($_SESSION['pending_registration'], $_SESSION['registration_otp']);
            $success_message = 'Xác thực thành công! Tài khoản đã được tạo. Vui lòng đăng nhập.';
            require __DIR__ . '/../views/auth/login.php';
        } else {
            $error = 'Đăng ký thất bại. Vui lòng thử lại.';
            require __DIR__ . '/../views/auth/register.php';
        }
    }
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
                // Kiểm tra trạng thái tài khoản
                if (isset($user['status']) && $user['status'] === 'locked') {
                    $error = 'Tài khoản của bạn đã bị khóa do vi phạm. Vui lòng liên hệ quản trị viên để được hỗ trợ.';
                    require __DIR__ . '/../views/auth/login.php';
                    return;
                }

                // Kiểm tra phạt quá hạn
                $fineModel = new Fine();
                $borrowingController = new BorrowingController();
                $fines = $fineModel->getByUserIdUnpaid($user['id']); // Cần thêm phương thức này vào FineModel

                $today = new DateTime();
                $accountShouldBeLocked = false;

                foreach ($fines as $fineItem) {
                    $fineCreatedAt = new DateTime($fineItem['created_at']);
                    $interval = $fineCreatedAt->diff($today);
                    if ($interval->days > BorrowingController::FINE_PAYMENT_GRACE_DAYS) {
                        $accountShouldBeLocked = true;
                        break;
                    }
                }

                if ($accountShouldBeLocked) {
                    $userModel->updateStatus($user['id'], 'locked');
                    $error = 'Tài khoản của bạn đã bị khóa do không thanh toán tiền phạt quá hạn. Vui lòng liên hệ quản trị viên để được hỗ trợ.';
                    require __DIR__ . '/../views/auth/login.php';
                    return;
                }

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
            // Lưu thông tin đăng ký chờ xác thực OTP
            $_SESSION['pending_registration'] = [
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'phone' => $phone,
                'address' => $address
            ];
            // Tạo và gửi OTP
            $otp = $this->generateOtp();
            $_SESSION['registration_otp'] = [
                'code' => $otp,
                'expires_at' => time() + 300 // 5 phút
            ];
            $emailService = new EmailService();
            $emailService->sendOtpEmail($email, $otp, $name);
            // Hiển thị form nhập OTP
            require __DIR__ . '/../views/auth/verify_otp.php';
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