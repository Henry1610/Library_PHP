<?php
require_once __DIR__ . '/../config/database.php';

class BookReview {
    private $conn;
    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Kiểm tra user đã đánh giá sách này trong đơn mượn này chưa
    public function hasReviewed($user_id, $book_id, $borrowing_id) {
        $stmt = $this->conn->prepare("SELECT 1 FROM book_reviews WHERE user_id = ? AND book_id = ? AND borrowing_id = ?");
        $stmt->bind_param('iii', $user_id, $book_id, $borrowing_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0;
    }

    // Thêm đánh giá mới
    public function addReview($user_id, $book_id, $borrowing_id, $rating, $comment = '') {
        $stmt = $this->conn->prepare("INSERT INTO book_reviews (user_id, book_id, borrowing_id, rating, comment, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param('iiiis', $user_id, $book_id, $borrowing_id, $rating, $comment);
        return $stmt->execute();
    }

    // Lấy tất cả đánh giá cho 1 sách
    public function getReviewsByBook($book_id) {
        $stmt = $this->conn->prepare("SELECT r.*, u.name as user_name FROM book_reviews r JOIN users u ON r.user_id = u.id WHERE r.book_id = ? ORDER BY r.created_at DESC");
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy đánh giá của user cho 1 sách trong 1 đơn mượn
    public function getUserReview($user_id, $book_id, $borrowing_id) {
        $stmt = $this->conn->prepare("SELECT * FROM book_reviews WHERE user_id = ? AND book_id = ? AND borrowing_id = ?");
        $stmt->bind_param('iii', $user_id, $book_id, $borrowing_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Lấy đánh giá theo ID
    public function getById($review_id) {
        $stmt = $this->conn->prepare("SELECT * FROM book_reviews WHERE id = ?");
        $stmt->bind_param('i', $review_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // Cập nhật đánh giá
    public function updateReview($review_id, $rating, $comment = '') {
        $stmt = $this->conn->prepare("UPDATE book_reviews SET rating = ?, comment = ?, updated_at = NOW() WHERE id = ?");
        $stmt->bind_param('isi', $rating, $comment, $review_id);
        return $stmt->execute();
    }

    // Xóa đánh giá
    public function deleteReview($review_id) {
        $stmt = $this->conn->prepare("DELETE FROM book_reviews WHERE id = ?");
        $stmt->bind_param('i', $review_id);
        return $stmt->execute();
    }

    // Tính điểm trung bình của sách
    public function getAverageRating($book_id) {
        $stmt = $this->conn->prepare("SELECT AVG(rating) as avg_rating FROM book_reviews WHERE book_id = ?");
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['avg_rating'] ? round($result['avg_rating'], 1) : 0;
    }

    // Lấy số lượng đánh giá của sách
    public function getReviewCount($book_id) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM book_reviews WHERE book_id = ?");
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'];
    }

    // Lấy phân bố đánh giá (1-5 sao)
    public function getRatingDistribution($book_id) {
        $stmt = $this->conn->prepare("
            SELECT rating, COUNT(*) as count 
            FROM book_reviews 
            WHERE book_id = ? 
            GROUP BY rating 
            ORDER BY rating DESC
        ");
        $stmt->bind_param('i', $book_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy đánh giá gần đây nhất
    public function getRecentReviews($limit = 10) {
        $stmt = $this->conn->prepare("
            SELECT r.*, u.name as user_name, b.title as book_title 
            FROM book_reviews r 
            JOIN users u ON r.user_id = u.id 
            JOIN books b ON r.book_id = b.id 
            ORDER BY r.created_at DESC 
            LIMIT ?
        ");
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Lấy đánh giá của user
    public function getUserReviews($user_id) {
        $stmt = $this->conn->prepare("
            SELECT r.*, b.title as book_title, b.cover_img 
            FROM book_reviews r 
            JOIN books b ON r.book_id = b.id 
            WHERE r.user_id = ? 
            ORDER BY r.created_at DESC
        ");
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }
} 