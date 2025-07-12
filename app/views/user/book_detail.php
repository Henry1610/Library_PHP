<?php include __DIR__ . '/../partials/user/header.php'; ?>
<?php
require_once __DIR__ . '/../../models/Book.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/BookReview.php';
require_once __DIR__ . '/../../models/Borrowing.php';
require_once __DIR__ . '/../../models/BorrowDetail.php';

$bookModel = new Book();
$categoryModel = new Category();
$bookReviewModel = new BookReview();
$borrowingModel = new Borrowing();
$borrowDetailModel = new BorrowDetail();

$book = null;
$categoryName = '';
$reviews = [];
$averageRating = 0;
$reviewCount = 0;
$userCanReview = false;
$userBorrowings = [];

if (isset($_GET['id'])) {
    $book = $bookModel->getById($_GET['id']);
    if ($book) {
        $cat = $categoryModel->getById($book['category_id']);
        $categoryName = $cat ? $cat['name'] : 'Không rõ';
        
        // Lấy đánh giá của sách
        $reviews = $bookReviewModel->getReviewsByBook($book['id']);
        $averageRating = $bookReviewModel->getAverageRating($book['id']);
        $reviewCount = $bookReviewModel->getReviewCount($book['id']);
        
        // Kiểm tra user có thể đánh giá không
        if (!empty($_SESSION['user'])) {
            $userBorrowings = $borrowingModel->getByUserId($_SESSION['user']['id']);
            foreach ($userBorrowings as $borrowing) {
                if ($borrowing['status'] === 'returned' || $borrowing['status'] === 'borrowed') {
                    $borrowDetails = $borrowDetailModel->getByBorrowingId($borrowing['id']);
                    foreach ($borrowDetails as $detail) {
                        if ($detail['book_id'] == $book['id']) {
                            // Kiểm tra đã đánh giá chưa
                            if (!$bookReviewModel->hasReviewed($_SESSION['user']['id'], $book['id'], $borrowing['id'])) {
                                $userCanReview = true;
                                $userCanReviewBorrowingId = $borrowing['id']; // Lưu borrowing_id để sử dụng
                                break 2;
                            }
                        }
                    }
                }
            }
        }
    }
}
?>
<style>
.book-detail-main {
  background: #fff;
  border-radius: 1.5rem;
  box-shadow: 0 4px 24px rgba(80,120,255,0.08);
  padding: 2rem 1.5rem;
  margin-bottom: 2rem;
}
.book-cover-img {
  width: 100%;
  aspect-ratio: 1/2;
  object-fit: cover;
  border-radius: 1.2rem;
  background: #f8fafd;
  min-height: 0;
  max-height: 600px;
  display: block;
  box-shadow: 0 2px 12px rgba(80,120,255,0.10);
}
@supports not (aspect-ratio: 1/2) {
  .book-cover-img { height: 400px; }
}
.book-info-title {
  font-size: 2.1rem;
  font-weight: 700;
  margin-bottom: 1.3rem;
}
.book-info-meta {
  font-size: 1.08rem;
  color: #555;
  margin-bottom: 1.1rem;
}
.book-info-badge {
  font-size: 1.08rem;
  font-weight: 600;
  border-radius: 1.2em;
  padding: 0.4em 1.1em;
  box-shadow: 0 2px 8px rgba(255,200,80,0.10);
}
.book-info-price {
  background: linear-gradient(90deg,#ffb347,#ffcc33);
  color: #fff;
}
.book-info-available {
  background: linear-gradient(90deg,#4f8cff,#6dd5ed);
  color: #fff;
}
.book-rating-stars {
  font-size: 1.5rem;
  color: #ffc107;
  letter-spacing: -2px;
}
.book-rating-count {
  font-size: 1.08rem;
  color: #888;
  margin-left: 0.5em;
}
.book-action-btns .btn {
  font-size: 1.1rem;
  border-radius: 2rem;
  padding: 0.5em 2em;
  font-weight: 600;
}
.book-action-btns .btn-success {
  background: linear-gradient(90deg,#4f8cff,#6dd5ed);
  border: none;
}
.book-action-btns .btn-success:hover {
  background: linear-gradient(90deg,#6dd5ed,#4f8cff);
}
.book-action-btns .btn-warning {
  background: linear-gradient(135deg, #ffc107 0%, #ffb347 100%);
  border: none;
  color: #000;
}
.book-action-btns .btn-warning:hover {
  background: linear-gradient(135deg, #ffb347 0%, #ffc107 100%);
  color: #000;
}
.review-card {
  border-radius: 1rem;
  box-shadow: 0 2px 8px rgba(80,120,255,0.06);
  margin-bottom: 1.5rem;
  background: #fafbff;
  padding: 1.2rem 1rem 1rem 1rem;
}
.review-user-avatar {
  width: 44px; height: 44px; border-radius: 50%; background: #eaf6ff; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: #4f8cff; font-weight: 700; margin-right: 1rem;
}
.review-user-name { font-weight: 600; }
.review-stars { color: #ffc107; font-size: 1.1rem; }
.review-date { color: #888; font-size: 0.98rem; }
.review-actions .btn { font-size: 0.95rem; border-radius: 1.2rem; padding: 0.2em 1.1em; }
@media (max-width: 767px) {
  .book-detail-main { padding: 1rem 0.5rem; }
  .book-info-title { font-size: 1.3rem; }
  .book-cover-img { max-height: 220px; }
}
</style>
<div class="container py-4">
<?php if (!$book): ?>
  <div class="alert alert-danger">Không tìm thấy sách!</div>
<?php else: ?>
  <div class="row book-detail-main align-items-start">
    <div class="col-md-4 mb-3 mb-md-0">
      <?php if (!empty($book['cover_img'])): ?>
        <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
      <?php else: ?>
        <div class="d-flex align-items-center justify-content-center bg-light book-cover-img">
          <span class="text-muted">Không có ảnh</span>
        </div>
      <?php endif; ?>
    </div>
    <div class="col-md-8">
      <div class="book-info-title mb-2"><?= htmlspecialchars($book['title']) ?></div>
      <div class="mb-2 d-flex align-items-center">
        <div class="book-rating-stars">
          <?php for ($i = 1; $i <= 5; $i++): ?>
            <i class="bi bi-star<?= $i <= $averageRating ? '-fill' : '' ?>"></i>
          <?php endfor; ?>
        </div>
        <span class="fw-bold ms-2"><?= $averageRating > 0 ? number_format($averageRating, 1) : 'Chưa có' ?></span>
        <span class="book-rating-count">(<?= $reviewCount ?> đánh giá)</span>
      </div>
      <div class="book-info-meta"><b>Tác giả:</b> <?= htmlspecialchars($book['author']) ?></div>
      <div class="book-info-meta"><b>Nhà xuất bản:</b> <?= htmlspecialchars($book['publisher']) ?></div>
      <div class="book-info-meta"><b>Năm xuất bản:</b> <?= htmlspecialchars($book['year']) ?></div>
      <div class="book-info-meta"><b>Danh mục:</b> <?= htmlspecialchars($categoryName) ?></div>
      <div class="book-info-meta"><b>ISBN:</b> <?= htmlspecialchars($book['isbn']) ?></div>
      <div class="book-info-meta"><b>Số lượng:</b> <?= $book['quantity'] ?></div>
      <div class="mb-4">
        <span class="book-info-badge book-info-available me-2">Còn lại: <?= $book['available'] ?></span>
        <span class="book-info-badge book-info-price">Giá: <?= number_format($book['price'], 0) ?> đ</span>
      </div>
      <?php if (!empty($book['description'])): ?>
        <div class="mb-4"><b>Mô tả:</b> <?= nl2br(htmlspecialchars($book['description'])) ?></div>
      <?php endif; ?>
      <div class="book-action-btns mt-4 d-flex gap-2 flex-wrap">
        <?php if (empty($_SESSION['user'])): ?>
          <a href="index.php?action=login" class="btn btn-outline-primary">Mượn</a>
        <?php else: ?>
          <button type="button" class="btn btn-success btn-borrow"
            data-bs-toggle="modal" data-bs-target="#borrowModal"
            data-book-id="<?= $book['id'] ?>"
            data-title="<?= htmlspecialchars($book['title']) ?>"
            data-available="<?= $book['available'] ?>">
            <i class="bi bi-bookmark-plus"></i> Mượn
          </button>
          <?php if ($userCanReview): ?>
            <a href="index.php?action=show_review_form&book_id=<?= $book['id'] ?>&borrowing_id=<?= $userCanReviewBorrowingId ?>" 
               class="btn btn-warning">
              <i class="bi bi-star"></i> Đánh giá
            </a>
          <?php endif; ?>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <!-- Reviews Section -->
  <div class="row mt-5">
    <div class="col-12">
      <div class="card shadow-sm">
        <div class="card-header bg-light">
          <h4 class="mb-0">
            <i class="bi bi-chat-dots me-2"></i>
            Đánh giá từ độc giả (<?= $reviewCount ?>)
          </h4>
        </div>
        <div class="card-body">
          <?php if (empty($reviews)): ?>
            <div class="text-center py-4">
              <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
              <p class="text-muted mt-2">Chưa có đánh giá nào cho sách này</p>
              <?php if (!empty($_SESSION['user']) && $userCanReview): ?>
                <a href="index.php?action=show_review_form&book_id=<?= $book['id'] ?>&borrowing_id=<?= $userCanReviewBorrowingId ?>" 
                   class="btn btn-primary">
                  <i class="bi bi-star"></i> Viết đánh giá đầu tiên
                </a>
              <?php endif; ?>
            </div>
          <?php else: ?>
            <div class="row">
              <?php foreach ($reviews as $review): ?>
                <div class="col-12">
                  <div class="review-card d-flex align-items-start">
                    <div class="review-user-avatar">
                      <span><?= strtoupper(mb_substr($review['user_name'], 0, 1, 'UTF-8')) ?></span>
                    </div>
                    <div class="flex-grow-1">
                      <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="review-user-name"><?= htmlspecialchars($review['user_name']) ?></span>
                        <span class="review-date">
                          <?= date('d/m/Y H:i', strtotime($review['created_at'])) ?>
                        </span>
                      </div>
                      <div class="review-stars mb-1">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                          <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill' : '' ?>"></i>
                        <?php endfor; ?>
                      </div>
                      <?php if (!empty($review['comment'])): ?>
                        <div class="mb-1"> <?= nl2br(htmlspecialchars($review['comment'])) ?> </div>
                      <?php endif; ?>
                      <?php if (!empty($_SESSION['user']) && $_SESSION['user']['id'] == $review['user_id']): ?>
                        <div class="review-actions mt-2 pt-2 border-top">
                          <a href="index.php?action=show_review_form&book_id=<?= $book['id'] ?>&borrowing_id=<?= $review['borrowing_id'] ?>" 
                             class="btn btn-sm btn-outline-primary me-2">
                            <i class="bi bi-pencil"></i> Sửa
                          </a>
                          <a href="index.php?action=delete_review&id=<?= $review['id'] ?>" 
                             class="btn btn-sm btn-outline-danger"
                             onclick="return confirm('Bạn có chắc muốn xóa đánh giá này?')">
                            <i class="bi bi-trash"></i> Xóa
                          </a>
                        </div>
                      <?php endif; ?>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>

<!-- Modal Bootstrap giữ nguyên như cũ -->
<div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?action=add_to_cart">
        <div class="modal-header">
          <h5 class="modal-title" id="borrowModalLabel">Mượn sách</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="book_id" id="modal-book-id">
          <div class="mb-3">
            <label for="modal-title" class="form-label">Tên sách</label>
            <input type="text" class="form-control" id="modal-title" readonly>
          </div>
          <div class="mb-3">
            <label for="modal-borrow-date" class="form-label">Ngày mượn</label>
            <input type="date" class="form-control" name="borrow_date" id="modal-borrow-date" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label for="modal-return-date" class="form-label">Ngày trả dự kiến</label>
            <input type="date" class="form-control" name="return_date" id="modal-return-date" min="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label for="modal-quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" name="quantity" id="modal-quantity" min="1" value="1" required>
            <div id="max-available" class="form-text"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Thêm vào giỏ mượn</button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.stars-display {
    display: inline-flex;
    gap: 0.1rem;
}

.stars-display i {
    font-size: 1.1rem;
}

.card {
    border: none;
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%) !important;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107 0%, #ffb347 100%);
    border: none;
    color: #000;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #ffb347 0%, #ffc107 100%);
    color: #000;
    transform: translateY(-1px);
}
</style>

<!-- Bootstrap JS & custom JS giữ nguyên như cũ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const borrowDateInput = document.getElementById('modal-borrow-date');
const returnDateInput = document.getElementById('modal-return-date');

borrowDateInput.addEventListener('change', function() {
    // Ngày trả không được nhỏ hơn ngày mượn
    returnDateInput.min = this.value;
    if (returnDateInput.value < this.value) {
        returnDateInput.value = this.value;
    }
});

document.addEventListener('DOMContentLoaded', function() {
    var borrowModal = document.getElementById('borrowModal');
    var bookIdInput = document.getElementById('modal-book-id');
    var titleInput = document.getElementById('modal-title');
    var quantityInput = document.getElementById('modal-quantity');
    var maxAvailable = document.getElementById('max-available');
    var borrowButtons = document.querySelectorAll('.btn-borrow');
    borrowButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var bookId = this.getAttribute('data-book-id');
            var title = this.getAttribute('data-title');
            var available = this.getAttribute('data-available');
            bookIdInput.value = bookId;
            titleInput.value = title;
            quantityInput.max = available;
            maxAvailable.textContent = 'Tối đa: ' + available + ' cuốn';
        });
    });
    borrowDateInput.min = '<?= date('Y-m-d') ?>';
    returnDateInput.min = borrowDateInput.value;
});
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 