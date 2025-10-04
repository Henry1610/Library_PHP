<?php 
$pageTitle = 'Danh Sách Sách - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body, .container, .card, .form-control, .btn, h1, h2, h3, h4, h5, h6 {
  font-family: 'Montserrat', Arial, sans-serif !important;
}
.card-modern {
  border-radius: 1.5rem !important;
  box-shadow: 0 4px 24px rgba(80,120,255,0.08);
  transition: box-shadow 0.2s, transform 0.2s;
  overflow: hidden;
  background: #fff;
  height: 320px;
  display: flex;
  flex-direction: column;
}
.card-modern:hover {
  box-shadow: 0 12px 36px rgba(80,120,255,0.16);
  transform: translateY(-4px) scale(1.025);
}
.card-modern .card-img-top, .book-cover-img {
  border-radius: 1.5rem 1.5rem 0 0 !important;
  transition: filter 0.2s;
  width: 100%;
  height: 320px;
  object-fit: cover;
}
.card-modern:hover .card-img-top, .card-modern:hover .book-cover-img {
  filter: brightness(0.93);
}
.card-modern .card-body {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  min-height: 0;
}
.card-modern .card-title {
  font-size: 1.08rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 0.5rem;
}
.card-modern .book-overlay {
  position: absolute; bottom: 1.2rem; left: 0; right: 0; opacity: 0; transition: opacity 0.2s;
  display: flex; justify-content: center;
}
.card-modern:hover .book-overlay {
  opacity: 1;
}
.card-modern .btn-borrow {
  border-radius: 2rem;
  font-weight: 600;
  font-size: 1.08rem;
  background: linear-gradient(90deg,#4f8cff,#6dd5ed);
  border: none;
  box-shadow: 0 2px 8px rgba(80,120,255,0.10);
  color: #fff;
  padding: 0.5em 2em;
}
.card-modern .btn-borrow:hover {
  background: linear-gradient(90deg,#6dd5ed,#4f8cff);
  color: #fff;
}
.badge-price {
  background: linear-gradient(90deg,#ffb347,#ffcc33);
  color: #fff;
  font-size: 1.08rem;
  font-weight: 600;
  border-radius: 1.2em;
  padding: 0.4em 1.1em;
  box-shadow: 0 2px 8px rgba(255,200,80,0.10);
}

/* Wishlist button styling */
.btn-wishlist {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255,255,255,0.9);
  border: none;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #6c757d;
  font-size: 1.1rem;
  transition: all 0.2s;
  z-index: 10;
  opacity: 0;
}

.card-modern:hover .btn-wishlist {
  opacity: 1;
}

.btn-wishlist:hover {
  background: #fff;
  color: #dc3545;
  transform: scale(1.1);
}

.btn-wishlist.active {
  color: #dc3545;
  background: rgba(255,255,255,0.95);
}

.btn-wishlist.active i {
  animation: heartBeat 0.3s ease-in-out;
}

@keyframes heartBeat {
  0% { transform: scale(1); }
  50% { transform: scale(1.3); }
  100% { transform: scale(1); }
}

/* Toast notification styling */
.toast-notification {
  position: fixed;
  top: 20px;
  right: 20px;
  background: white;
  border-radius: 8px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
  padding: 12px 16px;
  z-index: 9999;
  transform: translateX(100%);
  transition: transform 0.3s ease;
  max-width: 300px;
}

.toast-notification.show {
  transform: translateX(0);
}

.toast-notification.success {
  border-left: 4px solid #28a745;
}

.toast-notification.error {
  border-left: 4px solid #dc3545;
}

.toast-content {
  display: flex;
  align-items: center;
  gap: 8px;
}

.toast-content i {
  font-size: 1.1rem;
}

.toast-notification.success .toast-content i {
  color: #28a745;
}

.toast-notification.error .toast-content i {
  color: #dc3545;
}
@media (max-width: 767px) {
  .card-modern {height: 240px;}
  .card-modern .card-img-top, .book-cover-img {height: 100px;}
}
</style>
<div class="container py-4">
    <h2 class="mb-4 text-center">Tất cả Sách</h2>
    
    <!-- Thanh tìm kiếm -->
    <div class="row mb-4 justify-content-center">
        <div class="col-12 col-md-8 position-relative">
            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #6c757d; z-index: 10;"></i>
            <form method="get" action="" autocomplete="off" id="books-search-form">
                <input type="hidden" name="action" value="books">
                <input type="text" class="form-control ps-5 py-3 border-0 bg-light rounded-3" name="search" id="books-search-input"
                    placeholder="Tìm kiếm sách theo tên, tác giả hoặc ISBN..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>"
                    autocomplete="off">
                <div id="books-search-suggest" class="list-group position-absolute w-100 shadow-sm"
                    style="z-index:1000; top:110%; display:none;"></div>
            </form>
        </div>
    </div>
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
    // Lấy dữ liệu lọc và tìm kiếm
    $filter_category = $_GET['category'] ?? '';
    $filter_price_min = $_GET['price_min'] ?? '';
    $filter_price_max = $_GET['price_max'] ?? '';
    $filter_title = $_GET['title'] ?? '';
    $filter_author = $_GET['author'] ?? '';
    $search = $_GET['search'] ?? '';
    
    // Lọc sách
    $filteredBooks = array_filter($books, function($book) use ($filter_category, $filter_price_min, $filter_price_max, $filter_title, $filter_author, $search) {
        $ok = true;
        
        // Áp dụng bộ lọc
        if ($filter_category !== '' && $book['category_id'] != $filter_category) $ok = false;
        if ($filter_price_min !== '' && $book['price'] < floatval($filter_price_min)) $ok = false;
        if ($filter_price_max !== '' && $book['price'] > floatval($filter_price_max)) $ok = false;
        if ($filter_title !== '' && stripos($book['title'], $filter_title) === false) $ok = false;
        if ($filter_author !== '' && stripos($book['author'], $filter_author) === false) $ok = false;
        
        // Áp dụng tìm kiếm
        if ($search !== '' && $ok) {
            $ok = stripos($book['title'], $search) !== false || 
                  stripos($book['author'], $search) !== false || 
                  stripos($book['isbn'], $search) !== false;
        }
        
        return $ok;
    });
    ?>
    <div class="row">
        <!-- Sidebar bộ lọc -->
        <aside class="col-12 col-md-3 mb-4 mb-md-0">
            <div class="bg-white rounded-3 shadow-sm border-0 p-4 sticky-top" style="top:90px;z-index:1;">
                <form method="get" action="">
                    <input type="hidden" name="action" value="books">
                    <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                    <div class="d-flex align-items-center mb-4">
                        <i class="bi bi-funnel-fill text-primary me-2" style="font-size: 1.2rem;"></i>
                        <h5 class="mb-0 fw-bold text-dark">Bộ lọc tìm kiếm</h5>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="bi bi-bookmark-star me-1"></i>Danh mục
                        </label>
                        <select name="category" class="form-select border-0 bg-light rounded-3 py-2">
                            <option value="">Tất cả danh mục</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $filter_category == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="bi bi-currency-dollar me-1"></i>Khoảng giá
                        </label>
                        <div class="row g-2">
                            <div class="col-6">
                                <input type="number" name="price_min" class="form-control border-0 bg-light rounded-3 py-2" placeholder="Từ" min="0" value="<?= htmlspecialchars($filter_price_min) ?>">
                            </div>
                            <div class="col-6">
                                <input type="number" name="price_max" class="form-control border-0 bg-light rounded-3 py-2" placeholder="Đến" min="0" value="<?= htmlspecialchars($filter_price_max) ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="bi bi-search me-1"></i>Tên sách
                        </label>
                        <input type="text" name="title" class="form-control border-0 bg-light rounded-3 py-2" placeholder="Nhập tên sách..." value="<?= htmlspecialchars($filter_title) ?>">
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted mb-2">
                            <i class="bi bi-person me-1"></i>Tác giả
                        </label>
                        <input type="text" name="author" class="form-control border-0 bg-light rounded-3 py-2" placeholder="Nhập tên tác giả..." value="<?= htmlspecialchars($filter_author) ?>">
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold shadow-sm">
                        <i class="bi bi-funnel-fill me-2"></i>Áp dụng bộ lọc
                    </button>
                    
                    <?php if ($filter_category || $filter_price_min || $filter_price_max || $filter_title || $filter_author || $search): ?>
                    <div class="mt-3">
                        <a href="index.php?action=books" class="btn btn-outline-secondary w-100 rounded-3 py-2">
                            <i class="bi bi-x-circle me-2"></i>Xóa bộ lọc & tìm kiếm
                        </a>
                    </div>
                    <?php endif; ?>
                </form>
            </div>
        </aside>
        <!-- Lưới sách -->
        <section class="col-12 col-md-9">
            <div class="row g-4">
                <?php if (empty($filteredBooks)): ?>
                    <div class="col-12 text-center text-muted">Không tìm thấy sách phù hợp.</div>
                <?php endif; ?>
                <?php foreach ($filteredBooks as $book): ?>
                <div class="col-12 col-sm-6 col-md-4">
                    <div class="card card-modern h-100 position-relative">
                        <!-- Wishlist button -->
                        <button class="btn-wishlist" onclick="toggleWishlist(<?= $book['id'] ?>)" data-book-id="<?= $book['id'] ?>">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                        
                        <?php if (!empty($book['cover_img'])): ?>
                            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:120px;">
                                <span class="text-muted">Không có ảnh</span>
                            </div>
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2" title="<?= htmlspecialchars($book['title']) ?>">
                                <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
                            </h5>
                            <div class="mb-2"><small class="text-muted">Tác giả:</small> <?= htmlspecialchars($book['author']) ?></div>
                            <div class="mb-2"><small class="text-muted">Danh mục:</small> <?= isset($catMap[$book['category_id']]) ? htmlspecialchars($catMap[$book['category_id']]) : '<span class=\'text-danger\'>Không rõ</span>' ?></div>
                            <div class="mb-2">
                                <span class="badge badge-price"><?= number_format($book['price'], 0) ?> đ</span>
                                <span class="badge bg-info ms-1">
                                    <i class="bi bi-bookmark-check me-1"></i><?= number_format($book['borrow_count'] ?? 0) ?> lượt mượn
                                </span>
                            </div>
                            <div class="mt-auto position-relative">
                                <div class="book-overlay position-absolute w-100" style="bottom:0;left:0;">
                                    <?php if (empty($_SESSION['user'])): ?>
                                        <a href="index.php?action=login" class="btn btn-outline-primary w-100 btn-borrow">Mượn</a>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-borrow w-100 btn-borrow" 
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

<!-- Bootstrap JS & custom JS giữ nguyên như cũ -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Dữ liệu sách cho JS autocomplete
const BOOKS_DATA = <?php echo json_encode(array_map(function ($b) {
    return [
        'id' => $b['id'],
        'title' => $b['title'],
        'author' => $b['author'],
        'isbn' => $b['isbn'],
        'cover_img' => $b['cover_img'] ?? ''
    ];
}, $books)); ?>;

// Autocomplete cho thanh tìm kiếm
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('books-search-input');
    const suggestBox = document.getElementById('books-search-suggest');
    let debounceTimer = null;
    
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const val = this.value.trim();
        if (!val) { 
            suggestBox.style.display = 'none'; 
            return; 
        }
        
        debounceTimer = setTimeout(() => {
            const q = val.toLowerCase();
            const results = BOOKS_DATA.filter(b =>
                b.title.toLowerCase().includes(q) ||
                b.author.toLowerCase().includes(q) ||
                b.isbn.toLowerCase().includes(q)
            ).slice(0, 8);
            
            if (results.length === 0) { 
                suggestBox.style.display = 'none'; 
                return; 
            }
            
            suggestBox.innerHTML = results.map(b =>
                `<button type="button" class="list-group-item list-group-item-action d-flex align-items-center gap-2" data-title="${b.title.replace(/"/g, '&quot;')}">
                    ${b.cover_img ? `<img src="${b.cover_img}" style="width:32px;height:42px;object-fit:cover;border-radius:6px;">` : ''}
                    <span><b>${b.title}</b><br><small class="text-muted">${b.author} | ${b.isbn}</small></span>
                </button>`
            ).join('');
            suggestBox.style.display = 'block';
        }, 300);
    });
    
    // Xử lý click vào suggestion
    suggestBox.addEventListener('click', function(e) {
        if (e.target.closest('button')) {
            const button = e.target.closest('button');
            const title = button.getAttribute('data-title');
            searchInput.value = title;
            suggestBox.style.display = 'none';
            document.getElementById('books-search-form').submit();
        }
    });
    
    // Ẩn suggestion khi click ra ngoài
    document.addEventListener('click', function(e) {
        if (!e.target.closest('#books-search-form')) {
            suggestBox.style.display = 'none';
        }
    });
});
</script>
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
    
    // Kiểm tra trạng thái wishlist cho tất cả sách
    checkAllWishlistStatus();

    const borrowDateInput = document.getElementById('modal-borrow-date');
    const returnDateInput = document.getElementById('modal-return-date');

    borrowDateInput.addEventListener('change', function() {
        // Ngày trả không được nhỏ hơn ngày mượn
        returnDateInput.min = this.value;
        if (returnDateInput.value < this.value) {
            returnDateInput.value = this.value;
        }
    });
});

// Toggle wishlist
function toggleWishlist(bookId) {
    const button = document.querySelector(`[data-book-id="${bookId}"]`);
    const isActive = button.classList.contains('active');
    
    if (isActive) {
        // Xóa khỏi wishlist
        removeFromWishlist(bookId, button);
    } else {
        // Thêm vào wishlist
        addToWishlist(bookId, button);
    }
}

// Thêm vào wishlist
function addToWishlist(bookId, button) {
    fetch('index.php?action=add_to_wishlist', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'book_id=' + bookId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.add('active');
            showToast('Đã thêm vào danh sách yêu thích!', 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra!', 'error');
    });
}

// Xóa khỏi wishlist
function removeFromWishlist(bookId, button) {
    fetch('index.php?action=remove_from_wishlist', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'book_id=' + bookId
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            button.classList.remove('active');
            showToast('Đã xóa khỏi danh sách yêu thích!', 'success');
        } else {
            showToast(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra!', 'error');
    });
}

// Kiểm tra trạng thái wishlist cho tất cả sách
function checkAllWishlistStatus() {
    const wishlistButtons = document.querySelectorAll('.btn-wishlist');
    wishlistButtons.forEach(button => {
        const bookId = button.getAttribute('data-book-id');
        checkWishlistStatus(bookId, button);
    });
}

// Kiểm tra trạng thái wishlist cho một sách
function checkWishlistStatus(bookId, button) {
    fetch(`index.php?action=check_wishlist_status&book_id=${bookId}`)
    .then(response => response.json())
    .then(data => {
        if (data.success && data.in_wishlist) {
            button.classList.add('active');
        }
    })
    .catch(error => {
        console.error('Error checking wishlist status:', error);
    });
}

// Hiển thị toast notification
function showToast(message, type = 'info') {
    // Tạo toast element
    const toast = document.createElement('div');
    toast.className = `toast-notification ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <i class="fa-solid ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Thêm vào body
    document.body.appendChild(toast);
    
    // Hiển thị toast
    setTimeout(() => toast.classList.add('show'), 100);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 