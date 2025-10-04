<?php 
$activeSidebar = 'borrowings'; 
include __DIR__ . '/../partials/admin/header.php'; 
require_once __DIR__ . '/../../models/Fine.php';
$fineModel = new Fine();
?>
<style>
   
.user-info p {
  font-size: 0.78rem;
  line-height: 1.1;
  margin-bottom: 0.5rem !important; 
  color: #6c757d;
}
.user-info strong {
  font-weight: 600;
}
</style>

<div class="admin-layout d-flex">
  <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>

  <div class="main-content flex-grow-1 p-4 bg-light">
    <!-- Tiêu đề + nút hành động -->
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h4 class="fw-semibold text-secondary mb-1">
          <i class="fas fa-book-open me-2 text-muted"></i>Chi tiết yêu cầu mượn sách
        </h4>
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item">
              <a href="admin.php?action=borrowings_list" class="text-decoration-none">Quản lý mượn/trả</a>
            </li>
            <li class="breadcrumb-item active">Chi tiết #<?= $borrowing['id'] ?? '' ?></li>
          </ol>
        </nav>
      </div>
      <div class="btn-group">
        <a href="admin.php?action=borrowings_list" class="btn btn-outline-secondary btn-sm">
          <i class="fas fa-arrow-left me-1"></i>Quay lại
        </a>
        <?php if (($borrowing['approval_status'] ?? '') === 'pending'): ?>
          <button class="btn btn-outline-success btn-sm" onclick="approveBorrowing(<?= $borrowing['id'] ?? '' ?>)">
            <i class="fas fa-check me-1"></i>Duyệt mượn
          </button>
        <?php endif; ?>
        <?php if (($borrowing['status'] ?? '') === 'borrowed' && ($borrowing['return_approval_status'] ?? '') === 'pending'): ?>
          <button class="btn btn-outline-warning btn-sm" onclick="approveReturn(<?= $borrowing['id'] ?? '' ?>)">
            <i class="fas fa-undo me-1"></i>Duyệt trả
          </button>
        <?php endif; ?>
      </div>
    </div>

    <!-- Alert -->
    <?php if (!empty($error)): ?>
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    <?php endif; ?>

    <!-- Thông tin người mượn + yêu cầu -->
    <div class="row g-4">
      <!-- Card: Người mượn -->
      <div class="col-lg-4">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-semibold">
            <i class="fas fa-user me-2 text-muted"></i>Thông tin người mượn
          </div>
          <div class="card-body">
            <div class="mb-2">
              <h6 class="fw-semibold mb-1"><?= htmlspecialchars($user['name'] ?? 'Không xác định') ?></h6>
              <small class="text-muted">ID: #<?= $user['id'] ?? '' ?></small>
            </div>
            <hr>
            <div class="user-info">
  <p><strong>Email:</strong> <?= htmlspecialchars($user['email'] ?? '-') ?></p>
  <p><strong>Điện thoại:</strong> <?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></p>
  <p><strong>Địa chỉ:</strong> <?= htmlspecialchars($user['address'] ?? 'Chưa cập nhật') ?></p>
</div>

          </div>
        </div>
      </div>

      <!-- Card: Thông tin yêu cầu -->
      <div class="col-lg-8">
        <div class="card shadow-sm h-100">
          <div class="card-header bg-white fw-semibold">
            <i class="fas fa-info-circle me-2 text-muted"></i>Thông tin yêu cầu
          </div>
          <div class="card-body">
            <div class="row g-3">
              <div class="col-md-6">
                <div class="small text-muted">Mã yêu cầu</div>
                <div class="fw-semibold text-secondary">#<?= $borrowing['id'] ?? '' ?></div>
              </div>
              <div class="col-md-6">
                <div class="small text-muted">Ngày tạo</div>
                <div class="fw-semibold"><?= date('d/m/Y H:i', strtotime($borrowing['created_at'] ?? '')) ?></div>
              </div>

              <?php
                $statusMap = [
                    'borrowed' => ['bg-info', 'Đang mượn'],
                    'returned' => ['bg-success', 'Đã trả'],
                    'overdue' => ['bg-danger', 'Quá hạn']
                ];
                [$statusClass, $statusText] = $statusMap[$borrowing['status']] ?? ['bg-light text-dark border', ucfirst($borrowing['status'] ?? 'Chưa có')];

                $approvalMap = [
                    'pending' => ['bg-warning text-dark', 'Chờ duyệt'],
                    'approved' => ['bg-success', 'Đã duyệt'],
                    'rejected' => ['bg-danger', 'Từ chối']
                ];
                [$approvalClass, $approvalText] = $approvalMap[$borrowing['approval_status']] ?? ['bg-light text-dark border', ucfirst($borrowing['approval_status'] ?? 'Chưa có')];

                $returnMap = [
                    'pending' => ['bg-warning text-dark', 'Chờ duyệt'],
                    'approved' => ['bg-success', 'Đã duyệt']
                ];
                [$returnClass, $returnText] = $returnMap[$borrowing['return_approval_status']] ?? ['bg-light text-dark border', ucfirst($borrowing['return_approval_status'] ?? 'Chưa có')];
              ?>

              <div class="col-md-4">
                <div class="small text-muted">Trạng thái</div>
                <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
              </div>
              <div class="col-md-4">
                <div class="small text-muted">Duyệt mượn</div>
                <span class="badge <?= $approvalClass ?>"><?= $approvalText ?></span>
              </div>
              <div class="col-md-4">
                <div class="small text-muted">Duyệt trả</div>
                <span class="badge <?= $returnClass ?>"><?= $returnText ?></span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Card: Sách đã mượn -->
    <div class="card shadow-sm mt-4">
      <div class="card-header bg-white fw-semibold">
        <i class="fas fa-book me-2 text-muted"></i>Sách đã mượn
      </div>
      <div class="card-body">
        <?php if (!empty($borrowDetails)): ?>
          <div class="row g-3">
            <?php foreach ($borrowDetails as $detail): ?>
              <?php $book = $bookModel->getById($detail['book_id']); ?>
              <div class="col-12">
                <div class="d-flex align-items-center border rounded p-3 bg-white">
                  <div class="me-3">
                    <?php if (!empty($book['cover_img'])): ?>
                      <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="img-thumbnail" style="width:80px; height:110px; object-fit:cover;">
                    <?php else: ?>
                      <div class="d-flex align-items-center justify-content-center bg-light border rounded" style="width:80px; height:110px;">
                        <i class="fas fa-book text-muted"></i>
                      </div>
                    <?php endif; ?>
                  </div>
                  <div class="flex-grow-1">
                    <div class="fw-semibold"><?= htmlspecialchars($book['title'] ?? '') ?></div>
                    <small class="text-muted d-block">Tác giả: <?= htmlspecialchars($book['author'] ?? '-') ?></small>
                    <small class="text-muted d-block">NXB: <?= htmlspecialchars($book['publisher'] ?? '-') ?></small>
                  </div>
                  <div class="text-end">
                    <div class="small"><strong>Số lượng:</strong> <?= $detail['quantity'] ?? 1 ?></div>
                    <div class="small"><strong>Giá/ngày:</strong> <?= number_format($book['price'] ?? 0, 0) ?> đ</div>
                    <div class="small"><strong>Tổng:</strong> 
                      <span class="text-secondary"><?= number_format(($book['price'] ?? 0) * ($detail['quantity'] ?? 1), 0) ?> đ</span>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="text-center py-4 text-muted">
            <i class="fas fa-book-open fa-2x mb-2"></i>
            <div>Không có thông tin sách mượn</div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<script>
function approveBorrowing(id) {
  if (confirm('Duyệt yêu cầu mượn sách này?')) {
    window.location.href = `admin.php?action=approve_borrowing&id=${id}`;
  }
}
function approveReturn(id) {
  if (confirm('Duyệt yêu cầu trả sách này?')) {
    window.location.href = `admin.php?action=approve_return&id=${id}`;
  }
}
</script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?>
