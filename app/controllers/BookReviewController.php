<?php
require_once __DIR__ . '/../models/BookReview.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../models/Borrowing.php';
require_once __DIR__ . '/../models/BorrowDetail.php';

class BookReviewController {
    private $bookReviewModel;
    private $bookModel;
    private $borrowingModel;
    private $borrowDetailModel;

    public function __construct() {
        $this->bookReviewModel = new BookReview();
        $this->bookModel = new Book();
        $this->borrowingModel = new Borrowing();
        $this->borrowDetailModel = new BorrowDetail();
    }

    // Thêm đánh giá mới
    public function addReview() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $book_id = $_POST['book_id'] ?? null;
        $borrowing_id = $_POST['borrowing_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? '';

        // Validate dữ liệu
        if (!$book_id || !$borrowing_id || !$rating) {
            echo '<script>alert("Thiếu thông tin cần thiết!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra rating hợp lệ
        if ($rating < 1 || $rating > 5) {
            echo '<script>alert("Đánh giá phải từ 1-5 sao!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra user đã đánh giá chưa
        if ($this->bookReviewModel->hasReviewed($user_id, $book_id, $borrowing_id)) {
            echo '<script>alert("Bạn đã đánh giá sách này trong đơn mượn này rồi!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra đơn mượn có tồn tại và thuộc về user không
        $borrowing = $this->borrowingModel->getById($borrowing_id);
        if (!$borrowing || $borrowing['user_id'] != $user_id) {
            echo '<script>alert("Đơn mượn không hợp lệ!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra trạng thái đơn mượn (chỉ cho phép đánh giá khi đã mượn hoặc đã trả)
        if ($borrowing['status'] !== 'borrowed' && $borrowing['status'] !== 'returned') {
            echo '<script>alert("Chỉ có thể đánh giá sách đã mượn hoặc đã trả!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra sách có trong đơn mượn không
        $borrowDetails = $this->borrowDetailModel->getByBorrowingId($borrowing_id);
        $bookInBorrowing = false;
        foreach ($borrowDetails as $detail) {
            if ($detail['book_id'] == $book_id) {
                $bookInBorrowing = true;
                break;
            }
        }

        if (!$bookInBorrowing) {
            echo '<script>alert("Sách không có trong đơn mượn này!");window.history.back();</script>';
            exit;
        }

        // Thêm đánh giá
        if ($this->bookReviewModel->addReview($user_id, $book_id, $borrowing_id, $rating, $comment)) {
            echo '<script>alert("Đánh giá thành công!");window.location="index.php?action=book_detail&id='.$book_id.'";</script>';
        } else {
            echo '<script>alert("Có lỗi xảy ra!");window.history.back();</script>';
        }
    }

    // Cập nhật đánh giá
    public function updateReview() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php');
            exit;
        }

        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $review_id = $_POST['review_id'] ?? null;
        $rating = $_POST['rating'] ?? null;
        $comment = $_POST['comment'] ?? '';

        if (!$review_id || !$rating) {
            echo '<script>alert("Thiếu thông tin cần thiết!");window.history.back();</script>';
            exit;
        }

        if ($rating < 1 || $rating > 5) {
            echo '<script>alert("Đánh giá phải từ 1-5 sao!");window.history.back();</script>';
            exit;
        }

        // Kiểm tra đánh giá có tồn tại và thuộc về user không
        $review = $this->bookReviewModel->getById($review_id);
        if (!$review || $review['user_id'] != $user_id) {
            echo '<script>alert("Đánh giá không hợp lệ!");window.history.back();</script>';
            exit;
        }

        // Cập nhật đánh giá
        if ($this->bookReviewModel->updateReview($review_id, $rating, $comment)) {
            echo '<script>alert("Cập nhật đánh giá thành công!");window.location="index.php?action=book_detail&id='.$review['book_id'].'";</script>';
        } else {
            echo '<script>alert("Có lỗi xảy ra!");window.history.back();</script>';
        }
    }

    // Xóa đánh giá
    public function deleteReview() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $review_id = $_GET['id'] ?? null;

        if (!$review_id) {
            header('Location: index.php');
            exit;
        }

        // Kiểm tra đánh giá có tồn tại và thuộc về user không
        $review = $this->bookReviewModel->getById($review_id);
        if (!$review || $review['user_id'] != $user_id) {
            echo '<script>alert("Đánh giá không hợp lệ!");window.history.back();</script>';
            exit;
        }

        // Xóa đánh giá
        if ($this->bookReviewModel->deleteReview($review_id)) {
            echo '<script>alert("Xóa đánh giá thành công!");window.location="index.php?action=book_detail&id='.$review['book_id'].'";</script>';
        } else {
            echo '<script>alert("Có lỗi xảy ra!");window.history.back();</script>';
        }
    }

    // Hiển thị form đánh giá
    public function showReviewForm() {
        if (empty($_SESSION['user'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $book_id = $_GET['book_id'] ?? null;
        $borrowing_id = $_GET['borrowing_id'] ?? null;

        if (!$book_id || !$borrowing_id) {
            header('Location: index.php');
            exit;
        }

        $user_id = $_SESSION['user']['id'];
        $book = $this->bookModel->getById($book_id);
        
        if (!$book) {
            header('Location: index.php');
            exit;
        }

        // Kiểm tra đã đánh giá chưa
        $existingReview = $this->bookReviewModel->getUserReview($user_id, $book_id, $borrowing_id);

        require __DIR__ . '/../views/user/review_form.php';
    }

    // Lấy đánh giá cho một sách (API)
    public function getBookReviews() {
        $book_id = $_GET['book_id'] ?? null;
        
        if (!$book_id) {
            http_response_code(400);
            echo json_encode(['error' => 'Thiếu book_id']);
            exit;
        }

        $reviews = $this->bookReviewModel->getReviewsByBook($book_id);
        echo json_encode(['reviews' => $reviews]);
    }

    // Tính điểm trung bình của sách
    public function getAverageRating($book_id) {
        return $this->bookReviewModel->getAverageRating($book_id);
    }

    // Lấy số lượng đánh giá của sách
    public function getReviewCount($book_id) {
        return $this->bookReviewModel->getReviewCount($book_id);
    }
} 