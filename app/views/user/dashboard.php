<?php include __DIR__ . '/../partials/user/header.php'; ?>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body, .container, .card, .form-control, .btn, h1, h2, h3, h4, h5, h6 {
  font-family: 'Montserrat', Arial, sans-serif !important;
}
.dashboard-search-bar {
  border-radius: 2rem;
  box-shadow: 0 4px 24px rgba(80,120,255,0.08);
  border: none;
  font-size: 1.2rem;
  padding-left: 2.5rem;
  background: #f8fafd;
}
.dashboard-search-bar:focus {
  box-shadow: 0 6px 32px rgba(80,120,255,0.13);
  background: #fff;
}
.dashboard-search-icon {
  position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); color: #6c757d; font-size: 1.3rem;
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
.card-modern .card-img-top {
  border-radius: 1.5rem 1.5rem 0 0 !important;
  transition: filter 0.2s;
  height: 140px;
  object-fit: cover;
}
.card-modern:hover .card-img-top {
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
.section-title {
  font-weight: 700;
  font-size: 2rem;
  letter-spacing: -1px;
  margin-bottom: 1.2rem;
  display: flex; align-items: center; gap: 0.5em;
}
.section-title i {font-size: 1.5rem; color: #4f8cff;}
.suggest-card-modern {background: linear-gradient(120deg,#f8fafd 60%,#eaf6ff 100%);}
@media (max-width: 767px) {
  .section-title {font-size: 1.3rem;}
  .card-modern {height: 240px;}
  .card-modern .card-img-top {height: 100px;}
}
.book-marquee-wrapper {
  overflow: hidden;
  width: 100%;
  position: relative;
  background: #f8f9fa;
  border-radius: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
  margin-bottom: 2rem;
}
.book-marquee {
  display: flex;
  align-items: stretch;
  gap: 1.5rem;
  animation: marquee-scroll-book 22s linear infinite;
  will-change: transform;
}
.book-marquee:hover { animation-play-state: paused; }
@keyframes marquee-scroll-book {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
.book-marquee .card {
  min-width: 220px;
  max-width: 220px;
  height: 360px;
  display: flex;
  flex-direction: column;
}
@media (max-width: 767px) {
  .book-marquee .card { min-width: 150px; max-width: 150px; height: 260px; }
}
.book-cover-img {
  width: 100%;
  aspect-ratio: 3/4;
  object-fit: cover;
  border-radius: 1.5rem 1.5rem 0 0 !important;
  background: #f8fafd;
  min-height: 0;
  max-height: 340px;
  display: block;
}
@supports not (aspect-ratio: 3/4) {
  .book-cover-img {
    height: 240px;
  }
}
</style>
<div class="container py-4">
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
$newBooks = $books;
usort($newBooks, function($a, $b) { return $b['id'] - $a['id']; });
$newBooks = array_slice($newBooks, 0, 8);
$topBooks = $books;
foreach ($topBooks as &$b) {
    $b['borrowed'] = $b['borrow_count'] ?? 0;
}
usort($topBooks, function($a, $b) { return $b['borrowed'] - $a['borrowed']; });
$topBooks = array_slice($topBooks, 0, 5);
$excludeIds = array_merge(array_column($newBooks, 'id'), array_column($topBooks, 'id'));
$suggestBooks = array_filter($books, function($b) use ($excludeIds) { return !in_array($b['id'], $excludeIds); });
$suggestBooks = array_values($suggestBooks);
shuffle($suggestBooks);
$suggestBooks = array_slice($suggestBooks, 0, 4);
$search = $_GET['search'] ?? '';
$searchBooks = $books;
if ($search !== '') {
    $searchBooks = array_filter($books, function($b) use ($search) {
        return stripos($b['title'], $search) !== false || stripos($b['author'], $search) !== false || stripos($b['isbn'], $search) !== false;
    });
}
?>
<!-- Lời chào user -->
<?php if (!empty($_SESSION['user'])): ?>
<div class="mb-4 text-center">
  <h2 class="fw-bold" style="font-size:2.1rem;">Xin chào, <?= htmlspecialchars($_SESSION['user']['name'] ?? $_SESSION['user']['email']) ?>! 👋</h2>
  <div class="text-muted">Chúc bạn một ngày tốt lành và tìm được cuốn sách yêu thích!</div>
</div>
<?php endif; ?>
<!-- Thanh tìm kiếm hiện đại + autocomplete -->
<div class="row mb-4 justify-content-center">
  <div class="col-12 col-md-8 position-relative">
    <i class="bi bi-search dashboard-search-icon"></i>
    <form method="get" action="" autocomplete="off" id="dashboard-search-form">
      <input type="text" class="form-control dashboard-search-bar ps-5" name="search" id="dashboard-search-input" placeholder="Tìm kiếm sách theo tên, tác giả hoặc ISBN..." value="<?= htmlspecialchars($search) ?>" autocomplete="off">
      <div id="dashboard-search-suggest" class="list-group position-absolute w-100 shadow-sm" style="z-index:1000; top:110%; display:none;"></div>
    </form>
  </div>
</div>
<!-- Banner/Slider nổi bật -->
<div id="mainCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
  <div class="carousel-inner rounded shadow">
    <div class="carousel-item active">
      <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" style="max-height:340px;object-fit:cover;" alt="Thư viện">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
        <h3>Chào mừng đến với Thư viện Sách</h3>
        <p>Khám phá kho tri thức, mượn sách dễ dàng, trải nghiệm tuyệt vời!</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" style="max-height:340px;object-fit:cover;" alt="Sách mới">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
        <h3>Sách mới cập nhật mỗi tuần</h3>
        <p>Luôn có những đầu sách mới, đa dạng thể loại cho bạn lựa chọn.</p>
      </div>
    </div>
    <div class="carousel-item">
      <img src="https://images.unsplash.com/photo-1506744038136-46273834b3fb?auto=format&fit=crop&w=1200&q=80" class="d-block w-100" style="max-height:340px;object-fit:cover;" alt="Không gian đọc">
      <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded">
        <h3>Không gian đọc hiện đại</h3>
        <p>Thư viện thân thiện, tiện nghi, hỗ trợ bạn học tập và sáng tạo.</p>
      </div>
    </div>
  </div>
  <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Previous</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Next</span>
  </button>
</div>

<!-- Giới thiệu ngắn -->
<div class="row mb-4">
  <div class="col-12 text-center">
    <h2 class="fw-bold">Thư viện Sách - Nơi lan tỏa tri thức</h2>
    <p class="lead">Chúng tôi mang đến cho bạn hàng ngàn đầu sách đa dạng, không gian đọc hiện đại và dịch vụ mượn sách tiện lợi. Hãy khám phá, học hỏi và phát triển cùng Thư viện!</p>
  </div>
</div>

<!-- Danh mục nổi bật dạng marquee (cuộn ngang liên tục) -->
<style>
.category-marquee-wrapper {
  overflow: hidden;
  width: 100%;
  position: relative;
  background: #f8f9fa;
  border-radius: 1rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}
.category-marquee {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  animation: marquee-scroll 18s linear infinite;
  will-change: transform;
}
.category-marquee:hover {
  animation-play-state: paused;
}
@keyframes marquee-scroll {
  0% { transform: translateX(0); }
  100% { transform: translateX(-50%); }
}
</style>
<div class="row mb-4">
  <div class="col-12">
    <div class="category-marquee-wrapper py-3">
      <div class="category-marquee">
        <?php foreach ($categories as $cat): ?>
          <a href="index.php?action=books&category=<?= $cat['id'] ?>" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0">
            <i class="bi bi-bookmark-star me-2"></i><?= htmlspecialchars($cat['name']) ?>
          </a>
        <?php endforeach; ?>
        <!-- Lặp lại để tạo hiệu ứng vô hạn -->
        <?php foreach ($categories as $cat): ?>
          <a href="index.php?action=books&category=<?= $cat['id'] ?>" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm flex-shrink-0">
            <i class="bi bi-bookmark-star me-2"></i><?= htmlspecialchars($cat['name']) ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Sách mới nhất dạng marquee -->
<div class="section-title"><i class="bi bi-lightbulb"></i>Sách mới cập nhật</div>
<div class="book-marquee-wrapper py-3">
  <div class="book-marquee">
    <?php foreach ($newBooks as $book): ?>
      <div>
        <div class="card card-modern h-100 position-relative">
          <?php if (!empty($book['cover_img'])): ?>
            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:120px;">
              <span class="text-muted">Không có ảnh</span>
            </div>
          <?php endif; ?>
          <div class="card-body p-2">
            <h6 class="card-title mb-1" style="font-size:1.08rem;">
              <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
            </h6>
            <div class="small text-muted mb-1">Tác giả: <?= htmlspecialchars($book['author']) ?></div>
            <span class="badge badge-price"><?= number_format($book['price'], 0) ?> đ</span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
    <!-- Lặp lại để tạo hiệu ứng vô hạn -->
    <?php foreach ($newBooks as $book): ?>
      <div>
        <div class="card card-modern h-100 position-relative">
          <?php if (!empty($book['cover_img'])): ?>
            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:120px;">
              <span class="text-muted">Không có ảnh</span>
            </div>
          <?php endif; ?>
          <div class="card-body p-2">
            <h6 class="card-title mb-1" style="font-size:1.08rem;">
              <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
            </h6>
            <div class="small text-muted mb-1">Tác giả: <?= htmlspecialchars($book['author']) ?></div>
            <span class="badge badge-price"><?= number_format($book['price'], 0) ?> đ</span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<!-- Sách mượn nhiều nhất dạng lưới -->
<div class="section-title"><i class="bi bi-fire"></i>Sách được mượn nhiều nhất</div>
<div class="row g-3 mb-4">
  <?php foreach (array_slice($topBooks, 0, 4) as $book): ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="card card-modern h-100 position-relative border-warning border-2">
        <?php if (!empty($book['cover_img'])): ?>
          <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
        <?php else: ?>
          <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:120px;">
            <span class="text-muted">Không có ảnh</span>
          </div>
        <?php endif; ?>
        <div class="card-body p-2">
          <h6 class="card-title mb-1" style="font-size:1.08rem;">
            <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
          </h6>
          <div class="small text-muted mb-1">Tác giả: <?= htmlspecialchars($book['author']) ?></div>
          <span class="badge badge-price bg-warning text-dark">Đã mượn: <?= $book['borrowed'] ?> lượt</span>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<!-- Gợi ý cho bạn hiện đại -->
<?php if (count($suggestBooks) > 0): ?>
<div class="mb-4">
  <div class="section-title"><i class="bi bi-stars"></i>Gợi ý cho bạn</div>
  <div class="row g-3">
    <?php foreach ($suggestBooks as $book): ?>
      <div class="col-12 col-sm-6 col-md-3">
        <div class="card card-modern suggest-card-modern h-100 position-relative">
          <?php if (!empty($book['cover_img'])): ?>
            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
          <?php else: ?>
            <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:120px;">
              <span class="text-muted">Không có ảnh</span>
            </div>
          <?php endif; ?>
          <div class="card-body p-2">
            <h6 class="card-title mb-1" style="font-size:1.08rem;">
              <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
            </h6>
            <div class="small text-muted mb-1">Tác giả: <?= htmlspecialchars($book['author']) ?></div>
            <span class="badge badge-price"><?= number_format($book['price'], 0) ?> đ</span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>

<!-- Lưới sách cho mượn hiện đại -->
<div class="row mb-4">
    <div class="col-12">
        <div class="section-title"><i class="bi bi-book-half"></i>Danh sách Sách<?= $search ? ' (Kết quả tìm kiếm)' : '' ?></div>
    </div>
</div>
<div class="row g-4">
    <?php foreach ($searchBooks as $book): ?>
    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
        <div class="card card-modern h-100 position-relative">
            <?php if (!empty($book['cover_img'])): ?>
                <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover-img">
            <?php else: ?>
                <div class="d-flex align-items-center justify-content-center bg-light book-cover-img" style="min-height:180px;">
                    <span class="text-muted">Không có ảnh</span>
                </div>
            <?php endif; ?>
            <div class="card-body d-flex flex-column">
                <h5 class="card-title mb-2" title="<?= htmlspecialchars($book['title']) ?>">
                    <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="text-decoration-none stretched-link"> <?= htmlspecialchars($book['title']) ?> </a>
                </h5>
                <div class="mb-2"><small class="text-muted">Tác giả:</small> <?= htmlspecialchars($book['author']) ?></div>
                <div class="mb-2"><small class="text-muted">Danh mục:</small> <?= isset($catMap[$book['category_id']]) ? htmlspecialchars($catMap[$book['category_id']]) : '<span class=\'text-danger\'>Không rõ</span>' ?></div>
                <div class="mb-3"><span class="badge badge-price"><?= number_format($book['price'], 0) ?> đ</span></div>
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

<!-- Modal Bootstrap -->
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

<!-- Lý do nên chọn Thư viện Sách -->
<div class="row my-5">
  <div class="col-12 text-center mb-4">
    <h3 class="fw-bold">Tại sao nên chọn Thư viện Sách?</h3>
    <p class="text-muted">Chúng tôi cam kết mang lại trải nghiệm tốt nhất cho bạn!</p>
  </div>
  <div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card h-100 border-0 shadow-sm text-center p-3">
      <div class="mb-3 text-primary" style="font-size:2.5rem;"><i class="bi bi-book-half"></i></div>
      <h5 class="fw-bold">Kho sách đa dạng</h5>
      <p>Hàng ngàn đầu sách thuộc nhiều thể loại, luôn cập nhật mới liên tục.</p>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card h-100 border-0 shadow-sm text-center p-3">
      <div class="mb-3 text-success" style="font-size:2.5rem;"><i class="bi bi-people"></i></div>
      <h5 class="fw-bold">Phục vụ tận tâm</h5>
      <p>Đội ngũ nhân viên thân thiện, hỗ trợ bạn mọi lúc khi cần thiết.</p>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card h-100 border-0 shadow-sm text-center p-3">
      <div class="mb-3 text-warning" style="font-size:2.5rem;"><i class="bi bi-lightning-charge"></i></div>
      <h5 class="fw-bold">Mượn trả nhanh chóng</h5>
      <p>Thủ tục mượn trả đơn giản, thao tác online tiện lợi, tiết kiệm thời gian.</p>
    </div>
  </div>
  <div class="col-12 col-md-6 col-lg-3 mb-4">
    <div class="card h-100 border-0 shadow-sm text-center p-3">
      <div class="mb-3 text-danger" style="font-size:2.5rem;"><i class="bi bi-geo-alt"></i></div>
      <h5 class="fw-bold">Vị trí thuận tiện</h5>
      <p>Nằm ngay trung tâm thành phố, không gian hiện đại, dễ dàng di chuyển.</p>
    </div>
  </div>
</div>

<!-- Bootstrap JS & custom JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Dữ liệu sách cho JS autocomplete
const DASHBOARD_BOOKS = <?php echo json_encode(array_map(function($b) {
  return [
    'id' => $b['id'],
    'title' => $b['title'],
    'author' => $b['author'],
    'isbn' => $b['isbn'],
    'cover_img' => $b['cover_img'] ?? ''
  ];
}, $books)); ?>;
const searchInput = document.getElementById('dashboard-search-input');
const suggestBox = document.getElementById('dashboard-search-suggest');
let debounceTimer = null;
searchInput.addEventListener('input', function() {
  clearTimeout(debounceTimer);
  const val = this.value.trim();
  if (!val) { suggestBox.style.display = 'none'; return; }
  debounceTimer = setTimeout(() => {
    const q = val.toLowerCase();
    const results = DASHBOARD_BOOKS.filter(b =>
      b.title.toLowerCase().includes(q) ||
      b.author.toLowerCase().includes(q) ||
      b.isbn.toLowerCase().includes(q)
    ).slice(0, 8);
    if (results.length === 0) { suggestBox.style.display = 'none'; return; }
    suggestBox.innerHTML = results.map(b =>
      `<button type="button" class="list-group-item list-group-item-action d-flex align-items-center gap-2" data-title="${b.title.replace(/"/g,'&quot;')}">
        ${b.cover_img ? `<img src="${b.cover_img}" style="width:32px;height:42px;object-fit:cover;border-radius:6px;">` : ''}
        <span><b>${b.title}</b><br><small class="text-muted">${b.author} | ${b.isbn}</small></span>
      </button>`
    ).join('');
    suggestBox.style.display = 'block';
  }, 300);
});
document.addEventListener('click', function(e) {
  if (!suggestBox.contains(e.target) && e.target !== searchInput) {
    suggestBox.style.display = 'none';
  }
});
suggestBox.addEventListener('click', function(e) {
  const btn = e.target.closest('button[data-title]');
  if (btn) {
    searchInput.value = btn.getAttribute('data-title');
    suggestBox.style.display = 'none';
    document.getElementById('dashboard-search-form').submit();
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
});
</script>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 