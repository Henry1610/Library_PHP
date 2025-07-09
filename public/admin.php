<?php
session_start();
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/BookController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/controllers/BorrowingController.php';
require_once __DIR__ . '/../app/controllers/UserController.php';

if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: index.php?action=login');
    exit;
}

$action = $_GET['action'] ?? '';
$bookController = new BookController();
$categoryController = new CategoryController();
$borrowingController = new BorrowingController();
$userController = new UserController();

switch ($action) {
    case 'dashboard':
    case '':
        require __DIR__ . '/../app/views/admin/dashboard.php';
        break;
    case 'books':
        $bookController->list();
        break;
    case 'add_book':
        $bookController->add();
        break;
    case 'edit_book':
        $bookController->edit();
        break;
    case 'delete_book':
        $bookController->delete();
        break;
    case 'categories':
        $categoryController->list();
        break;
    case 'add_category':
        $categoryController->add();
        break;
    case 'edit_category':
        $categoryController->edit();
        break;
    case 'delete_category':
        $categoryController->delete();
        break;
    case 'borrowings_list':
        $borrowingController->listAll();
        break;
    case 'approve_borrowing':
        $id = $_GET['id'] ?? null;
        if ($id) $borrowingController->approveBorrowing($id);
        break;
    case 'approve_return':
        $id = $_GET['id'] ?? null;
        if ($id) $borrowingController->approveReturn($id);
        break;
    case 'borrowing_detail':
        $borrowingController->detail();
        break;
    case 'users':
        $activeSidebar = 'users';
        $userController->list();
        break;
    case 'update_user_role':
        $userController->updateRole();
        break;
    case 'delete_user':
        $userController->delete();
        break;
    case 'reviews':
        $activeSidebar = 'reviews';
        require __DIR__ . '/../app/views/admin/reviews.php';
        break;
    case 'delete_review':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $review_id = $_GET['id'] ?? null;
            if ($review_id) {
                require_once __DIR__ . '/../app/models/BookReview.php';
                $bookReviewModel = new BookReview();
                if ($bookReviewModel->deleteReview($review_id)) {
                    echo 'success';
                } else {
                    echo 'error';
                }
            }
        }
        break;
    default:
        require __DIR__ . '/../app/views/admin/dashboard.php';
        break;
} 