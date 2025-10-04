<?php 
$activeSidebar = 'borrowings';
$pageTitle = 'Quản Lý Mượn/Trả - Admin E-Library';
include __DIR__ . '/../partials/admin/header.php'; 
?>

<div class="admin-layout d-flex">
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>

    <div class="main-content container-fluid py-4">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold mb-0">
                <i class="fas fa-book-open me-2 text-primary"></i>Quản lý Mượn / Trả sách
            </h3>
        </div>

        <!-- Bộ lọc tìm kiếm -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-center">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên người dùng...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bảng danh sách -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="selectAll">
                                        <label class="form-check-label fw-semibold" for="selectAll">ID</label>
                                    </div>
                                </th>
                                <th>Người mượn</th>
                                <th class="text-center">Số lượng</th>
                                <th>Trạng thái</th>
                                <th>Duyệt mượn</th>
                                <th>Duyệt trả</th>
                                <th>Ngày tạo</th>
                                <th class="text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowings as $b): ?>
                                <?php $u = $userModel->getById($b['user_id']); ?>
                                <tr class="borrowing-row" 
                                    data-id="<?= $b['id'] ?>" 
                                    data-status="<?= $b['status'] ?>" 
                                    data-approval="<?= $b['approval_status'] ?>">
                                    
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input borrowing-checkbox" type="checkbox" value="<?= $b['id'] ?>">
                                            <label class="form-check-label fw-bold">#<?= $b['id'] ?></label>
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($u['name'] ?? 'Không xác định') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($u['email'] ?? '') ?></small>
                                        </div>
                                    </td>

                                    <td class="text-center fw-bold"><?= (int)($b['quantity'] ?? 1) ?></td>

                                    <td>
                                        <?php
                                            $statusMap = [
                                                'pending' => ['bg-warning text-dark', 'Chờ duyệt'],
                                                'borrowed' => ['bg-primary', 'Đã mượn'],
                                                'returned' => ['bg-success', 'Đã trả'],
                                                'overdue' => ['bg-danger', 'Quá hạn']
                                            ];
                                            [$statusClass, $statusText] = $statusMap[$b['status']] ?? ['bg-secondary', 'Không rõ'];
                                        ?>
                                        <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                    </td>

                                    <td>
                                        <?php
                                            $approvalMap = [
                                                'pending' => ['bg-warning text-dark', 'Chờ duyệt'],
                                                'approved' => ['bg-success', 'Đã duyệt'],
                                                'rejected' => ['bg-danger', 'Từ chối']
                                            ];
                                            [$approvalClass, $approvalText] = $approvalMap[$b['approval_status']] ?? ['bg-secondary', 'Không rõ'];
                                        ?>
                                        <span class="badge <?= $approvalClass ?>"><?= $approvalText ?></span>
                                    </td>

                                    <td>
                                        <?php
                                            $returnMap = [
                                                'pending' => ['bg-warning text-dark', 'Chờ duyệt'],
                                                'approved' => ['bg-success', 'Đã duyệt'],
                                                'rejected' => ['bg-danger', 'Từ chối']
                                            ];
                                            [$returnClass, $returnText] = $returnMap[$b['return_approval_status']] ?? ['bg-secondary', 'Không rõ'];
                                        ?>
                                        <span class="badge <?= $returnClass ?>"><?= $returnText ?></span>
                                    </td>

                                    <td>
                                        <small class="text-muted"><?= date('d/m/Y H:i', strtotime($b['created_at'])) ?></small>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="admin.php?action=borrowing_detail&id=<?= $b['id'] ?>" 
                                               class="btn btn-outline-primary" data-bs-toggle="tooltip" title="Xem chi tiết">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($b['approval_status'] === 'pending'): ?>
                                            <button class="btn btn-outline-success" data-bs-toggle="tooltip" title="Duyệt mượn"
                                                onclick="approveBorrowing(<?= $b['id'] ?>)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <?php endif; ?>
                                            <?php if ($b['status'] === 'borrowed' && $b['return_approval_status'] === 'pending'): ?>
                                            <button class="btn btn-outline-warning" data-bs-toggle="tooltip" title="Duyệt trả"
                                                onclick="approveReturn(<?= $b['id'] ?>)">
                                                <i class="fas fa-undo"></i>
                                            </button>
                                            <?php endif; ?>
                                            <button class="btn btn-outline-danger" data-bs-toggle="tooltip" title="Xóa"
                                                onclick="deleteBorrowing(<?= $b['id'] ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Phân trang -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <small class="text-muted">
                Hiển thị <span id="showingCount"><?= count($borrowings) ?></span> / <span id="totalCount"><?= count($borrowings) ?></span> yêu cầu
            </small>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link">Trước</a></li>
                    <li class="page-item active"><a class="page-link">1</a></li>
                    <li class="page-item disabled"><a class="page-link">Sau</a></li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
// --- Search ---
document.getElementById('searchInput').addEventListener('input', filterBorrowings);

function filterBorrowings() {
    const term = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.borrowing-row');
    let visible = 0;
    rows.forEach(row => {
        const name = row.querySelector('.fw-semibold').textContent.toLowerCase();
        if (name.includes(term)) {
            row.style.display = '';
            visible++;
        } else row.style.display = 'none';
    });
    document.getElementById('showingCount').textContent = visible;
}
function clearFilters() {
    document.getElementById('searchInput').value = '';
    filterBorrowings();
}
document.getElementById('selectAll').addEventListener('change', e => {
    document.querySelectorAll('.borrowing-checkbox').forEach(cb => cb.checked = e.target.checked);
});

function approveBorrowing(id) {
    if (confirm('Xác nhận duyệt yêu cầu mượn sách này?')) 
        window.location.href = `admin.php?action=approve_borrowing&id=${id}`;
}
function approveReturn(id) {
    if (confirm('Xác nhận duyệt trả sách này?')) 
        window.location.href = `admin.php?action=approve_return&id=${id}`;
}
function deleteBorrowing(id) {
    if (confirm('Bạn có chắc muốn xóa yêu cầu này?')) 
        window.location.href = `admin.php?action=delete_borrowing&id=${id}`;
}
</script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?>
