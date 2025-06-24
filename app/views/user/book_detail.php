<?php include __DIR__ . '/../partials/user/header.php'; ?>
<?php
require_once __DIR__ . '/../../models/Book.php';
require_once __DIR__ . '/../../models/Category.php';
$bookModel = new Book();
$categoryModel = new Category();
$book = null;
$categoryName = '';
if (isset($_GET['id'])) {
    $book = $bookModel->getById($_GET['id']);
    if ($book) {
        $cat = $categoryModel->getById($book['category_id']);
        $categoryName = $cat ? $cat['name'] : 'Không rõ';
    }
}
?>
<div class="container py-4">
    <?php if (!$book): ?>
        <div class="alert alert-danger">Không tìm thấy sách!</div>
    <?php else: ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="p-4 bg-primary text-white rounded shadow-sm text-center">
                    <h1 class="mb-1">Chi Tiết Sách</h1>
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <?php if (!empty($book['cover_img'])): ?>
                    <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="img-fluid rounded shadow-sm mb-3">
                <?php else: ?>
                    <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height:300px;">
                        <span class="text-muted">Không có ảnh</span>
                    </div>
                <?php endif; ?>
            </div>
            <div class="col-md-8">
                <h2><?= htmlspecialchars($book['title']) ?></h2>
                <p><strong>Tác giả:</strong> <?= htmlspecialchars($book['author']) ?></p>
                <p><strong>Nhà xuất bản:</strong> <?= htmlspecialchars($book['publisher']) ?></p>
                <p><strong>Năm xuất bản:</strong> <?= htmlspecialchars($book['year']) ?></p>
                <p><strong>Danh mục:</strong> <?= htmlspecialchars($categoryName) ?></p>
                <p><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?></p>
                <p><strong>Số lượng:</strong> <?= $book['quantity'] ?></p>
                <p><strong>Còn lại:</strong> <span class="fw-bold text-success"><?= $book['available'] ?></span></p>
                <p><strong>Giá:</strong> <span class="fw-bold text-danger"><?= number_format($book['price'], 2) ?> đ</span></p>
                <?php if (!empty($book['description'])): ?>
                    <p><strong>Mô tả:</strong> <?= nl2br(htmlspecialchars($book['description'])) ?></p>
                <?php endif; ?>
                <div class="mt-4">
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
                    <?php endif; ?>
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
            <input type="date" class="form-control" name="borrow_date" id="modal-borrow-date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label for="modal-return-date" class="form-label">Ngày trả dự kiến</label>
            <input type="date" class="form-control" name="return_date" id="modal-return-date" required>
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

<!-- Bootstrap JS & custom JS giữ nguyên như cũ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
});
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 