<?php $activeSidebar = 'borrowings'; include __DIR__ . '/../partials/admin/header.php'; ?>

<?php
require_once __DIR__ . '/../../models/Fine.php';
$fineModel = new Fine();
?>

<style>
.card {
    border: none;
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border-radius: 0.5rem;
}
.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem 0.5rem 0 0 !important;
    border: none;
}
.status-badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}
.book-item {
    border-left: 4px solid #667eea;
    background: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
    margin-bottom: 1rem;
}
.book-cover {
    width: 80px;
    height: 120px;
    object-fit: cover;
    border-radius: 0.375rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
.timeline {
    position: relative;
    padding-left: 2rem;
}
.timeline::before {
    content: '';
    position: absolute;
    left: 0.75rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #dee2e6;
}
.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}
.timeline-item::before {
    content: '';
    position: absolute;
    left: -1.5rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    background: #667eea;
    border: 3px solid white;
    box-shadow: 0 0 0 2px #667eea;
}
</style>

<div class="admin-layout">
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">
                    <i class="fas fa-book-open me-2"></i>Chi tiết yêu cầu mượn sách
                </h2>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="admin.php?action=borrowings_list">Quản lý mượn/trả</a></li>
                        <li class="breadcrumb-item active">Chi tiết #<?= $borrowing['id'] ?? '' ?></li>
                    </ol>
                </nav>
            </div>
            <div class="d-flex gap-2">
                <a href="admin.php?action=borrowings_list" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                </a>
                <?php if (($borrowing['approval_status'] ?? '') === 'pending'): ?>
                <button class="btn btn-success" onclick="approveBorrowing(<?= $borrowing['id'] ?? '' ?>)">
                    <i class="fas fa-check me-2"></i>Duyệt mượn
                </button>
                <?php endif; ?>
                <?php if (($borrowing['status'] ?? '') === 'borrowed' && ($borrowing['return_approval_status'] ?? '') === 'pending'): ?>
                <button class="btn btn-warning" onclick="approveReturn(<?= $borrowing['id'] ?? '' ?>)">
                    <i class="fas fa-undo me-2"></i>Duyệt trả
                </button>
                <?php endif; ?>
            </div>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="row">
            <!-- Thông tin người mượn -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>Thông tin người mượn
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="mb-1"><?= htmlspecialchars($user['name'] ?? 'Không xác định') ?></h6>
                            <small class="text-muted">ID: #<?= $user['id'] ?? '' ?></small>
                        </div>
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small">Email</label>
                                <div class="fw-semibold"><?= htmlspecialchars($user['email'] ?? '') ?></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">Số điện thoại</label>
                                <div class="fw-semibold"><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></div>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">Địa chỉ</label>
                                <div class="fw-semibold"><?= htmlspecialchars($user['address'] ?? 'Chưa cập nhật') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Thông tin yêu cầu -->
            <div class="col-lg-8 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-info-circle me-2"></i>Thông tin yêu cầu
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Mã yêu cầu</label>
                                <div class="fw-bold text-primary">#<?= $borrowing['id'] ?? '' ?></div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Ngày tạo</label>
                                <div class="fw-semibold"><?= date('d/m/Y H:i', strtotime($borrowing['created_at'] ?? '')) ?></div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Trạng thái mượn</label>
                                <div>
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    $statusText = $borrowing['status'] ?? '';
                                    switch($borrowing['status'] ?? '') {
                                        case 'pending': $statusClass = 'bg-warning'; $statusText = 'Chờ duyệt'; break;
                                        case 'borrowed': $statusClass = 'bg-primary'; $statusText = 'Đã mượn'; break;
                                        case 'returned': $statusClass = 'bg-success'; $statusText = 'Đã trả'; break;
                                        case 'overdue': $statusClass = 'bg-danger'; $statusText = 'Quá hạn'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?> status-badge"><?= $statusText ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Duyệt mượn</label>
                                <div>
                                    <?php
                                    $approvalClass = 'bg-secondary';
                                    $approvalText = $borrowing['approval_status'] ?? '';
                                    switch($borrowing['approval_status'] ?? '') {
                                        case 'pending': $approvalClass = 'bg-warning'; $approvalText = 'Chờ duyệt'; break;
                                        case 'approved': $approvalClass = 'bg-success'; $approvalText = 'Đã duyệt'; break;
                                        case 'rejected': $approvalClass = 'bg-danger'; $approvalText = 'Từ chối'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $approvalClass ?> status-badge"><?= $approvalText ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-muted small">Duyệt trả</label>
                                <div>
                                    <?php
                                    $returnClass = 'bg-secondary';
                                    $returnText = $borrowing['return_approval_status'] ?? '';
                                    switch($borrowing['return_approval_status'] ?? '') {
                                        case 'pending': $returnClass = 'bg-warning'; $returnText = 'Chờ duyệt'; break;
                                        case 'approved': $returnClass = 'bg-success'; $returnText = 'Đã duyệt'; break;
                                        case 'rejected': $returnClass = 'bg-danger'; $returnText = 'Từ chối'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $returnClass ?> status-badge"><?= $returnText ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danh sách sách mượn -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-books me-2"></i>Sách đã mượn
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($borrowDetails)): ?>
                    <?php foreach ($borrowDetails as $detail): ?>
                    <?php $book = $bookModel->getById($detail['book_id']); ?>
                    <div class="book-item">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-center">
                                <?php if (!empty($book['cover_img'])): ?>
                                    <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover">
                                <?php else: ?>
                                    <div class="book-cover bg-secondary d-flex align-items-center justify-content-center text-white">
                                        <span class="fw-bold"><?= strtoupper(substr($book['title'] ?? 'B', 0, 1)) ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <h6 class="mb-1"><?= htmlspecialchars($book['title'] ?? '') ?></h6>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-user me-1"></i><?= htmlspecialchars($book['author'] ?? '') ?>
                                </p>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-building me-1"></i><?= htmlspecialchars($book['publisher'] ?? '') ?>
                                    <?php if (!empty($book['year'])): ?>
                                        (<?= htmlspecialchars($book['year']) ?>)
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="col-md-4">
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="fw-bold text-primary"><?= $detail['quantity'] ?? 1 ?></div>
                                        <small class="text-muted">Số lượng</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-success"><?= number_format($book['price'] ?? 0, 0) ?> đ</div>
                                        <small class="text-muted">Giá/ngày</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="fw-bold text-info"><?= number_format(($book['price'] ?? 0) * ($detail['quantity'] ?? 1), 0) ?> đ</div>
                                        <small class="text-muted">Tổng</small>
                                    </div>
                                </div>
                                <div class="mt-2 text-start">
                                    <small class="text-muted d-block">Mượn: <?= date('d/m/Y', strtotime($detail['borrow_date'])) ?></small>
                                    <small class="text-muted d-block">Dự kiến trả: <?= date('d/m/Y', strtotime($detail['return_date'])) ?></small>
                                    <?php
                                    $today = new DateTime();
                                    $returnDate = new DateTime($detail['return_date']);
                                    if ($today > $returnDate) {
                                        $interval = $today->diff($returnDate);
                                        $overdueDays = $interval->days;
                                        $fine = $fineModel->getByBorrowingId($borrowing['id']);
                                        if ($fine && $fine['amount'] > 0) {
                                            echo '<span class="badge bg-danger mt-1">Quá hạn: ' . $overdueDays . ' ngày</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có thông tin sách mượn</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function approveBorrowing(borrowingId) {
    if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu mượn sách này?')) {
        window.location.href = `admin.php?action=approve_borrowing&id=${borrowingId}`;
    }
}

function approveReturn(borrowingId) {
    if (confirm('Bạn có chắc chắn muốn duyệt yêu cầu trả sách này?')) {
        window.location.href = `admin.php?action=approve_return&id=${borrowingId}`;
    }
}
</script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 