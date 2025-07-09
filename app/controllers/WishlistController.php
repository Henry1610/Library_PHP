<?php
require_once __DIR__ . '/../models/Wishlist.php';
require_once __DIR__ . '/../models/Book.php';

class WishlistController {
    private $wishlistModel;
    private $bookModel;
    
    public function __construct() {
        $this->wishlistModel = new Wishlist();
        $this->bookModel = new Book();
    }
    
    // Hiển thị trang wishlist
    public function showWishlist() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $user_id = $_SESSION['user']['id'];
        $wishlist = $this->wishlistModel->getUserWishlist($user_id);
        require __DIR__ . '/../views/user/wishlist.php';
    }
    
    // Thêm sách vào wishlist
    public function addToWishlist() {
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $user_id = $_SESSION['user']['id'];
        $book_id = $_POST['book_id'] ?? null;
        
        if (!$book_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sách']);
            return;
        }
        
        // Kiểm tra sách có tồn tại không
        $book = $this->bookModel->getById($book_id);
        if (!$book) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Sách không tồn tại']);
            return;
        }
        
        $result = $this->wishlistModel->addToWishlist($user_id, $book_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã thêm vào danh sách yêu thích']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Sách đã có trong danh sách yêu thích']);
        }
    }
    
    // Xóa sách khỏi wishlist
    public function removeFromWishlist() {
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $user_id = $_SESSION['user']['id'];
        $book_id = $_POST['book_id'] ?? null;
        
        if (!$book_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sách']);
            return;
        }
        
        $result = $this->wishlistModel->removeFromWishlist($user_id, $book_id);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Đã xóa khỏi danh sách yêu thích']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Không thể xóa sách']);
        }
    }
    
    // Kiểm tra trạng thái wishlist
    public function checkWishlistStatus() {
        if (empty($_SESSION['user'])) {
            http_response_code(401);
            echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
            return;
        }
        
        $user_id = $_SESSION['user']['id'];
        $book_id = $_GET['book_id'] ?? null;
        
        if (!$book_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sách']);
            return;
        }
        
        $isInWishlist = $this->wishlistModel->isInWishlist($user_id, $book_id);
        echo json_encode(['success' => true, 'in_wishlist' => $isInWishlist]);
    }
    
    // Xóa tất cả wishlist
    public function clearWishlist() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $user_id = $_SESSION['user']['id'];
        $result = $this->wishlistModel->clearWishlist($user_id);
        
        if ($result) {
            echo '<script>alert("Đã xóa tất cả sách khỏi danh sách yêu thích!");window.location="index.php?action=wishlist";</script>';
        } else {
            echo '<script>alert("Không thể xóa danh sách yêu thích!");window.location="index.php?action=wishlist";</script>';
        }
    }
} 