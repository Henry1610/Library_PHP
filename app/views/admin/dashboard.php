<?php include __DIR__ . '/../partials/admin/header.php'; ?>
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
    <div class="main-content">
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="fas fa-book"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold mb-0"><?= $totalBooks ?></div>
                            <div class="text-muted">Số lượng sách</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="fas fa-layer-group"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold mb-0"><?= $totalCategories ?></div>
                            <div class="text-muted">Số lượng danh mục</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold mb-0"><?= $totalUsers ?></div>
                            <div class="text-muted">Số lượng người dùng</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body d-flex align-items-center">
                        <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;font-size:1.5rem;">
                            <i class="fas fa-coins"></i>
                        </div>
                        <div>
                            <div class="fs-4 fw-bold mb-0"><?= number_format($totalRevenue, 0) ?> đ</div>
                            <div class="text-muted">Tổng doanh thu</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-primary text-white fw-bold">Top 3 sách được mượn nhiều nhất</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($topBorrowedBooks as $book): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= htmlspecialchars($book['title']) ?></span>
                                <span class="badge bg-primary rounded-pill"><?= (int)$book['borrow_count'] ?> lượt mượn</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-danger text-white fw-bold">Top 3 sách ít số lượng nhất</div>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($lowestQuantityBooks as $book): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span><?= htmlspecialchars($book['title']) ?></span>
                                <span class="badge bg-danger rounded-pill">Còn <?= (int)$book['quantity'] ?> cuốn</span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-12 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-secondary text-white fw-bold">5 lượt mượn gần đây nhất</div>
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">ID</th>
                                    <th scope="col">Người mượn</th>
                                    <th scope="col">Ngày mượn</th>
                                    <th scope="col">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentBorrowings as $borrowing): ?>
                                    <?php $user = $userModel->getById($borrowing['user_id']); ?>
                                    <tr>
                                        <td><?= $borrowing['id'] ?></td>
                                        <td><?= htmlspecialchars($user['name'] ?? 'Không rõ') ?></td>
                                        <td><?= htmlspecialchars($borrowing['created_at'] ?? '') ?></td>
                                        <td><?= htmlspecialchars($borrowing['status'] ?? '') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Xóa phần chào mừng và mô tả hướng dẫn ở đây -->
    </div>
</div>
<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 