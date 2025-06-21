<?php
session_start();
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/BookController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/models/Book.php';
$action = $_GET['action'] ?? '';
$auth = new AuthController();
$bookController = new BookController();
$categoryController = new CategoryController();
$bookModel = new Book();

switch ($action) {
    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;
    case 'logout':
        $auth->logout();
        break;
    case 'books':
        if ($_SESSION['user']['role'] === 'admin') {
            $bookController->list();
        } else {
            header('Location: index.php');
        }
        break;
    case 'add_book':
        if ($_SESSION['user']['role'] === 'admin') {
            $bookController->add();
        } else {
            header('Location: index.php');
        }
        break;
    case 'edit_book':
        if ($_SESSION['user']['role'] === 'admin') {
            $bookController->edit();
        } else {
            header('Location: index.php');
        }
        break;
    case 'delete_book':
        if ($_SESSION['user']['role'] === 'admin') {
            $bookController->delete();
        } else {
            header('Location: index.php');
        }
        break;
    case 'categories':
        if ($_SESSION['user']['role'] === 'admin') {
            $categoryController->list();
        } else {
            header('Location: index.php');
        }
        break;
    case 'add_category':
        if ($_SESSION['user']['role'] === 'admin') {
            $categoryController->add();
        } else {
            header('Location: index.php');
        }
        break;
    case 'edit_category':
        if ($_SESSION['user']['role'] === 'admin') {
            $categoryController->edit();
        } else {
            header('Location: index.php');
        }
        break;
    case 'delete_category':
        if ($_SESSION['user']['role'] === 'admin') {
            $categoryController->delete();
        } else {
            header('Location: index.php');
        }
        break;
    case 'admin':
        if ($_SESSION['user']['role'] === 'admin') {
            require __DIR__ . '/../app/views/admin/dashboard.php';
        } else {
            header('Location: index.php');
        }
        break;
    case 'borrow_cart':
        require __DIR__ . '/../app/views/user/borrow_cart.php';
        break;
    case 'add_to_cart':
    case 'remove_from_cart':
    case 'checkout':
        echo '<script>alert("Chức năng mượn sách đã bị tắt!");window.location="index.php";</script>';
        exit;
    default:
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            require __DIR__ . '/../app/views/admin/dashboard.php';
        } else {
            require __DIR__ . '/../app/views/user/dashboard.php';
        }
        break;
} 