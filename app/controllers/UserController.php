<?php
require_once __DIR__ . '/../models/User.php';
class UserController {
    public function list() {
        $userModel = new User();
        $users = $userModel->getAll();
        require __DIR__ . '/../views/admin/users/list.php';
    }
    public function updateRole() {
        if (isset($_POST['user_id'], $_POST['role'])) {
            $userModel = new User();
            $userModel->updateRole($_POST['user_id'], $_POST['role']);
        }
        header('Location: admin.php?action=users');
        exit;
    }
    public function delete() {
        if (isset($_GET['id'])) {
            $userModel = new User();
            $userModel->delete($_GET['id']);
        }
        header('Location: admin.php?action=users');
        exit;
    }
} 