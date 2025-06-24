<?php
session_start();
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/BookController.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/controllers/BorrowingController.php';
require_once __DIR__ . '/../app/models/Book.php';
$action = $_GET['action'] ?? '';
$auth = new AuthController();
$bookController = new BookController();
$categoryController = new CategoryController();
$bookModel = new Book();
$borrowingController = new BorrowingController();

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
        require __DIR__ . '/../app/views/user/books.php';
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
        if (!empty($_SESSION['user'])) {
            $book_id = $_POST['book_id'] ?? null;
            $quantity = $_POST['quantity'] ?? 1;
            $borrow_date = $_POST['borrow_date'] ?? date('Y-m-d');
            $return_date = $_POST['return_date'] ?? date('Y-m-d', strtotime('+7 days'));
            if ($book_id) {
                $_SESSION['cart'][] = [
                    'book_id' => $book_id,
                    'quantity' => $quantity,
                    'borrow_date' => $borrow_date,
                    'return_date' => $return_date
                ];
            }
            header('Location: index.php?action=borrow_cart');
        } else {
            header('Location: index.php?action=login');
        }
        break;
    case 'remove_from_cart':
        if (!empty($_SESSION['user'])) {
            $key = $_GET['key'] ?? null;
            if ($key !== null && isset($_SESSION['cart'][$key])) {
                unset($_SESSION['cart'][$key]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
            header('Location: index.php?action=borrow_cart');
        } else {
            header('Location: index.php?action=login');
        }
        break;
    case 'checkout':
        if (!empty($_SESSION['user'])) {
            require_once __DIR__ . '/../app/models/Borrowing.php';
            require_once __DIR__ . '/../app/models/BorrowDetail.php';
            $borrowingModel = new Borrowing();
            $borrowDetailModel = new BorrowDetail();
            $user_id = $_SESSION['user']['id'];
            $borrowing_id = $borrowingModel->add($user_id, 'pending');
            foreach ($_SESSION['cart'] as $item) {
                $borrowDetailModel->add($borrowing_id, $item['book_id'], $item['quantity'], $item['borrow_date'], $item['return_date']);
            }
            unset($_SESSION['cart']);
            echo '<script>alert("Gửi yêu cầu mượn thành công! Chờ admin duyệt.");window.location="index.php";</script>';
        } else {
            header('Location: index.php?action=login');
        }
        break;
    case 'approve_borrowing':
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $id = $_GET['id'] ?? null;
            if ($id) $borrowingController->approveBorrowing($id);
        } else {
            header('Location: index.php');
        }
        break;
    case 'borrowing_payment':
        if (!empty($_SESSION['user'])) {
            $id = $_GET['id'] ?? null;
            if ($id) $borrowingController->confirmBorrowingPayment($id);
        } else {
            header('Location: index.php');
        }
        break;
    case 'approve_return':
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $id = $_GET['id'] ?? null;
            if ($id) $borrowingController->approveReturn($id);
        } else {
            header('Location: index.php');
        }
        break;
    case 'return_payment':
        if (!empty($_SESSION['user'])) {
            $id = $_GET['id'] ?? null;
            if ($id) $borrowingController->confirmReturnPayment($id);
        } else {
            header('Location: index.php');
        }
        break;
    case 'borrowings_list':
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            $borrowingController->listAll();
        } else {
            header('Location: index.php');
        }
        break;
    case 'request_return':
        if (!empty($_SESSION['user'])) {
            require_once __DIR__ . '/../app/models/Borrowing.php';
            $borrowingModel = new Borrowing();
            $id = $_GET['id'] ?? null;
            if ($id) {
                $borrowingModel->updateReturnApprovalStatus($id, 'pending');
                echo '<script>alert("Đã gửi yêu cầu trả sách. Chờ admin duyệt!");window.location="index.php";</script>';
            } else {
                header('Location: index.php');
            }
        } else {
            header('Location: index.php?action=login');
        }
        break;
    case 'borrowing_history':
        if (!empty($_SESSION['user'])) {
            require __DIR__ . '/../app/views/user/borrowing_history.php';
        } else {
            header('Location: index.php?action=login');
        }
        break;
    case 'book_detail':
        require __DIR__ . '/../app/views/user/book_detail.php';
        break;
    case 'contact':
        require __DIR__ . '/../app/views/user/contact.php';
        break;
    case 'help':
        require __DIR__ . '/../app/views/user/help.php';
        break;
    default:
        if (!empty($_SESSION['user']) && $_SESSION['user']['role'] === 'admin') {
            require __DIR__ . '/../app/views/admin/dashboard.php';
        } else {
            require __DIR__ . '/../app/views/user/dashboard.php';
        }
        break;
} 