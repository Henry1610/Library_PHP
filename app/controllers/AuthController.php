<?php
require_once __DIR__ . '/../models/User.php';
// session_start();

class AuthController {
    public function showLogin() {
        require __DIR__ . '/../views/auth/login.php';
    }
    public function showRegister() {
        require __DIR__ . '/../views/auth/register.php';
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
                header('Location: index.php?action=login');
                exit;
            } else {
                $error = 'Đăng ký thất bại!';
                require __DIR__ . '/../views/auth/register.php';
            }
        }
    }
    public function logout() {
        session_destroy();
        header('Location: index.php?action=login');
        exit;
    }
} 