<?php $activeSidebar = 'borrowings'; include __DIR__ . '/../partials/admin/header.php'; ?>

<style>
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05);
}
.btn-group .btn {
    border-radius: 0.375rem !important;
}
.btn-group .btn:first-child {
    border-top-left-radius: 0.375rem !important;
    border-bottom-left-radius: 0.375rem !important;
}
.btn-group .btn:last-child {
    border-top-right-radius: 0.375rem !important;
    border-bottom-right-radius: 0.375rem !important;
}
</style>

<div class="admin-layout">
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-book-open me-2"></i>Quản lý Mượn/Trả sách
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="exportBorrowings()">
                    <i class="fas fa-download me-2"></i>Xuất Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBorrowingModal">
                    <i class="fas fa-plus me-2"></i>Tạo yêu cầu mượn
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên người dùng...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="borrowed">Đã mượn</option>
                            <option value="returned">Đã trả</option>
                            <option value="overdue">Quá hạn</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select class="form-select" id="approvalFilter">
                            <option value="">Tất cả duyệt</option>
                            <option value="pending">Chờ duyệt</option>
                            <option value="approved">Đã duyệt</option>
                            <option value="rejected">Từ chối</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Lọc theo ngày">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowings Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2" id="selectAll">
                                        ID
                                    </div>
                                </th>
                                <th class="border-0">Người mượn</th>
                                <th class="border-0">Sách mượn</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0">Duyệt mượn</th>
                                <th class="border-0">Duyệt trả</th>
                                <th class="border-0">Ngày tạo</th>
                                <th class="border-0 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($borrowings as $b): ?>
                            <?php $u = $userModel->getById($b['user_id']); ?>
                            <tr class="borrowing-row" data-status="<?= $b['status'] ?>" data-approval="<?= $b['approval_status'] ?>">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 borrowing-checkbox" value="<?= $b['id'] ?>">
                                        <span class="fw-bold">#<?= $b['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <?= strtoupper(substr($u['name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($u['name'] ?? 'Không xác định') ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($u['email'] ?? '') ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="fw-bold"><?= (int)($b['quantity'] ?? 1) ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $statusClass = 'bg-secondary';
                                    $statusText = $b['status'];
                                    switch($b['status']) {
                                        case 'pending': $statusClass = 'bg-warning'; $statusText = 'Chờ duyệt'; break;
                                        case 'borrowed': $statusClass = 'bg-primary'; $statusText = 'Đã mượn'; break;
                                        case 'returned': $statusClass = 'bg-success'; $statusText = 'Đã trả'; break;
                                        case 'overdue': $statusClass = 'bg-danger'; $statusText = 'Quá hạn'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>"><?= $statusText ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $approvalClass = 'bg-secondary';
                                    $approvalText = $b['approval_status'];
                                    switch($b['approval_status']) {
                                        case 'pending': $approvalClass = 'bg-warning'; $approvalText = 'Chờ duyệt'; break;
                                        case 'approved': $approvalClass = 'bg-success'; $approvalText = 'Đã duyệt'; break;
                                        case 'rejected': $approvalClass = 'bg-danger'; $approvalText = 'Từ chối'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $approvalClass ?>"><?= $approvalText ?></span>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $returnClass = 'bg-secondary';
                                    $returnText = $b['return_approval_status'];
                                    switch($b['return_approval_status']) {
                                        case 'pending': $returnClass = 'bg-warning'; $returnText = 'Chờ duyệt'; break;
                                        case 'approved': $returnClass = 'bg-success'; $returnText = 'Đã duyệt'; break;
                                        case 'rejected': $returnClass = 'bg-danger'; $returnText = 'Từ chối'; break;
                                    }
                                    ?>
                                    <span class="badge <?= $returnClass ?>"><?= $returnText ?></span>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($b['created_at'])) ?>
                                    </small>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="Xem chi tiết"
                                                onclick="viewBorrowing(<?= $b['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <?php if ($b['approval_status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success" 
                                                data-bs-toggle="tooltip" title="Duyệt mượn"
                                                onclick="approveBorrowing(<?= $b['id'] ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if ($b['status'] === 'borrowed' && $b['return_approval_status'] === 'pending'): ?>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="tooltip" title="Duyệt trả"
                                                onclick="approveReturn(<?= $b['id'] ?>)">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Xóa"
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

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Hiển thị <span id="showingCount"><?= count($borrowings) ?></span> trong tổng số <span id="totalCount"><?= count($borrowings) ?></span> yêu cầu
            </div>
            <nav aria-label="Borrowing pagination">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Trước</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled">
                        <a class="page-link" href="#">Sau</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Add Borrowing Modal -->
<div class="modal fade" id="addBorrowingModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Tạo yêu cầu mượn sách
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="admin.php?action=add_borrowing">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Người dùng *</label>
                            <select class="form-select" name="user_id" required>
                                <option value="">Chọn người dùng</option>
                                <!-- Add user options here -->
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Sách *</label>
                            <select class="form-select" name="book_id" required>
                                <option value="">Chọn sách</option>
                                <!-- Add book options here -->
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Số lượng</label>
                            <input type="number" class="form-control" name="quantity" value="1" min="1">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Ngày trả dự kiến</label>
                            <input type="date" class="form-control" name="return_date" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Tạo yêu cầu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Borrowing Detail Modal -->
<div class="modal fade" id="borrowingDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book-open me-2"></i>Chi tiết yêu cầu mượn sách
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="borrowingDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterBorrowings();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterBorrowings();
});

document.getElementById('approvalFilter').addEventListener('change', function() {
    filterBorrowings();
});

document.getElementById('dateFilter').addEventListener('change', function() {
    filterBorrowings();
});

function filterBorrowings() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const approvalFilter = document.getElementById('approvalFilter').value;
    const dateFilter = document.getElementById('dateFilter').value;
    const rows = document.querySelectorAll('.borrowing-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const userName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const status = row.dataset.status;
        const approval = row.dataset.approval;
        const createdDate = row.querySelector('.text-muted').textContent;
        
        const matchesSearch = userName.includes(searchTerm);
        const matchesStatus = !statusFilter || status === statusFilter;
        const matchesApproval = !approvalFilter || approval === approvalFilter;
        const matchesDate = !dateFilter || createdDate.includes(dateFilter);
        
        if (matchesSearch && matchesStatus && matchesApproval && matchesDate) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    document.getElementById('showingCount').textContent = visibleCount;
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('approvalFilter').value = '';
    document.getElementById('dateFilter').value = '';
    filterBorrowings();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.borrowing-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function viewBorrowing(borrowingId) {
    const modal = new bootstrap.Modal(document.getElementById('borrowingDetailModal'));
    document.getElementById('borrowingDetailContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Simulate loading borrowing details
    setTimeout(() => {
        document.getElementById('borrowingDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Thông tin người mượn</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tên:</strong></td><td>Nguyễn Văn A</td></tr>
                        <tr><td><strong>Email:</strong></td><td>nguyenvana@example.com</td></tr>
                        <tr><td><strong>Số điện thoại:</strong></td><td>0123456789</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Thông tin sách</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tên sách:</strong></td><td>Lập trình PHP</td></tr>
                        <tr><td><strong>Tác giả:</strong></td><td>John Doe</td></tr>
                        <tr><td><strong>Số lượng:</strong></td><td>1</td></tr>
                    </table>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6>Trạng thái yêu cầu</h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-warning">Chờ duyệt mượn</span>
                        <span class="badge bg-secondary">Chưa trả</span>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

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

function deleteBorrowing(borrowingId) {
    if (confirm('Bạn có chắc chắn muốn xóa yêu cầu này?')) {
        window.location.href = `admin.php?action=delete_borrowing&id=${borrowingId}`;
    }
}

function exportBorrowings() {
    alert('Chức năng xuất Excel sẽ được thêm sau');
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 