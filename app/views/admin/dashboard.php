<?php 
$pageTitle = 'Dashboard - Admin E-Library';
include __DIR__ . '/../partials/admin/header.php'; 
?>
<?php
require_once __DIR__ . '/../../models/Book.php';
require_once __DIR__ . '/../../models/Category.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Transaction.php';
require_once __DIR__ . '/../../models/Borrowing.php';

$bookModel = new Book();
$categoryModel = new Category();
$userModel = new User();
$transactionModel = new Transaction();
$borrowingModel = new Borrowing();

$totalBooks = $bookModel->countAll();
$totalCategories = $categoryModel->countAll();
$totalUsers = $userModel->countAll();
$totalRevenue = $transactionModel->getTotalRevenue();
$topBorrowedBooks = $bookModel->getMostBorrowed(3);
$lowestQuantityBooks = $bookModel->getLowestQuantity(3);
$recentBorrowings = $borrowingModel->getRecent(5);
?>

<div class="admin-layout d-flex">
  <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>

  <div class="main-content container-fluid py-4">
    <!-- Thống kê -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <div class="text-primary mb-2"><i class="bi bi-book fs-1"></i></div>
            <h4 class="fw-bold mb-0"><?= $totalBooks ?></h4>
            <div class="text-muted small">Sách</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <div class="text-success mb-2"><i class="bi bi-collection fs-1"></i></div>
            <h4 class="fw-bold mb-0"><?= $totalCategories ?></h4>
            <div class="text-muted small">Danh mục</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <div class="text-info mb-2"><i class="bi bi-people fs-1"></i></div>
            <h4 class="fw-bold mb-0"><?= $totalUsers ?></h4>
            <div class="text-muted small">Người dùng</div>
          </div>
        </div>
      </div>
      <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center h-100">
          <div class="card-body">
            <div class="text-warning mb-2"><i class="bi bi-cash-coin fs-1"></i></div>
            <h4 class="fw-bold mb-0"><?= number_format($totalRevenue, 0) ?> đ</h4>
            <div class="text-muted small">Doanh thu</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Top sách -->
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-primary bg-opacity-10 fw-semibold">
            <i class="bi bi-bookmark-star text-primary me-2"></i>Top 3 sách được mượn nhiều nhất
          </div>
          <ul class="list-group list-group-flush">
            <?php foreach ($topBorrowedBooks as $book): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($book['title']) ?></span>
                <span class="badge text-bg-primary"><?= (int)$book['borrow_count'] ?> lượt</span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
          <div class="card-header bg-danger bg-opacity-10 fw-semibold">
            <i class="bi bi-exclamation-triangle text-danger me-2"></i>Top 3 sách ít số lượng nhất
          </div>
          <ul class="list-group list-group-flush">
            <?php foreach ($lowestQuantityBooks as $book): ?>
              <li class="list-group-item d-flex justify-content-between align-items-center">
                <span><?= htmlspecialchars($book['title']) ?></span>
                <span class="badge text-bg-danger">Còn <?= (int)$book['quantity'] ?> cuốn</span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      </div>
    </div>

    <!-- Lượt mượn gần đây -->
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-secondary bg-opacity-10 fw-semibold">
        <i class="bi bi-clock-history text-secondary me-2"></i>5 lượt mượn gần đây
      </div>
      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>ID</th>
              <th>Người mượn</th>
              <th>Ngày mượn</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($recentBorrowings as $borrowing): ?>
              <?php $user = $userModel->getById($borrowing['user_id']); ?>
              <tr>
                <td class="fw-semibold">#<?= $borrowing['id'] ?></td>
                <td><?= htmlspecialchars($user['name'] ?? 'Không rõ') ?></td>
                <td><?= htmlspecialchars(date('d/m/Y', strtotime($borrowing['created_at'] ?? ''))) ?></td>
                <td>
                  <?php if (($borrowing['status'] ?? '') === 'returned'): ?>
                    <span class="badge text-bg-success">Đã trả</span>
                  <?php elseif (($borrowing['status'] ?? '') === 'borrowing'): ?>
                    <span class="badge text-bg-warning">Đang mượn</span>
                  <?php else: ?>
                    <span class="badge text-bg-secondary"><?= htmlspecialchars($borrowing['status'] ?? '') ?></span>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?>
