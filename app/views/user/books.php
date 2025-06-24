<?php include __DIR__ . '/../partials/user/header.php'; ?>
<div class="container py-4">
    <h2 class="mb-4 text-center">Tất cả Sách</h2>
    <?php
    require_once __DIR__ . '/../../models/Book.php';
    require_once __DIR__ . '/../../models/Category.php';
    $bookModel = new Book();
    $categoryModel = new Category();
    $books = $bookModel->getAll();
    $categories = $categoryModel->getAll();
    $catMap = [];
    foreach ($categories as $cat) {
        $catMap[$cat['id']] = $cat['name'];
    }
    // Lấy dữ liệu lọc
    $filter_category = $_GET['category'] ?? '';
    $filter_price_min = $_GET['price_min'] ?? '';
    $filter_price_max = $_GET['price_max'] ?? '';
    $filter_title = $_GET['title'] ?? '';
    $filter_author = $_GET['author'] ?? '';
    // Lọc sách
    $filteredBooks = array_filter($books, function($book) use ($filter_category, $filter_price_min, $filter_price_max, $filter_title, $filter_author) {
        $ok = true;
        if ($filter_category !== '' && $book['category_id'] != $filter_category) $ok = false;
        if ($filter_price_min !== '' && $book['price'] < floatval($filter_price_min)) $ok = false;
        if ($filter_price_max !== '' && $book['price'] > floatval($filter_price_max)) $ok = false;
        if ($filter_title !== '' && stripos($book['title'], $filter_title) === false) $ok = false;
        if ($filter_author !== '' && stripos($book['author'], $filter_author) === false) $ok = false;
        return $ok;
    });
    ?>
    <div class="row">
        <!-- Sidebar bộ lọc -->
        <aside class="col-12 col-md-3 mb-4 mb-md-0">
            <form class="bg-light rounded shadow-sm p-3 sticky-top" style="top:90px;z-index:1;" method="get" action="">
                <input type="hidden" name="action" value="books">
                <h5 class="mb-3"><i class="bi bi-funnel"></i> Bộ lọc</h5>
                <div class="mb-3">
                    <label class="form-label">Danh mục</label>
                    <select name="category" class="form-select">
                        <option value="">Tất cả</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $filter_category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Giá từ</label>
                    <input type="number" name="price_min" class="form-control" min="0" value="<?= htmlspecialchars($filter_price_min) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Đến</label>
                    <input type="number" name="price_max" class="form-control" min="0" value="<?= htmlspecialchars($filter_price_max) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tên sách</label>
                    <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($filter_title) ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Tác giả</label>
                    <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($filter_author) ?>">
                </div>
                <button type="submit" class="btn btn-primary w-100"><i class="bi bi-funnel"></i> Lọc</button>
            </form>
        </aside>
        <!-- Lưới sách -->
        <section class="col-12 col-md-9">
            <div class="row g-4">
                <?php if (empty($filteredBooks)): ?>
                    <div class="col-12 text-center text-muted">Không tìm thấy sách phù hợp.</div>
                <?php endif; ?>
                <?php foreach ($filteredBooks as $book): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($book['cover_img'])): ?>
                            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="card-img-top" style="object-fit:cover;max-height:220px;min-height:180px;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                <span class="text-muted">Không có ảnh</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2" title="<?= htmlspecialchars($book['title']) ?>">
                                <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
                            </h5>
                            <div class="mb-2"><small class="text-muted">Tác giả:</small> <?= htmlspecialchars($book['author']) ?></div>
                            <div class="mb-2"><small class="text-muted">Danh mục:</small> <?= isset($catMap[$book['category_id']]) ? htmlspecialchars($catMap[$book['category_id']]) : '<span class=\'text-danger\'>Không rõ</span>' ?></div>
                            <div class="mb-3"><small class="text-muted">Giá:</small> <span class="fw-bold text-danger"><?= number_format($book['price'], 2) ?> đ</span></div>
                            <div class="mt-auto">
                                <?php if (empty($_SESSION['user'])): ?>
                                    <a href="index.php?action=login" class="btn btn-outline-primary w-100">Mượn</a>
                                <?php else: ?>
                                    <button type="button" class="btn btn-success w-100 btn-borrow" 
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
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
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