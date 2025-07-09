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
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text">
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
                            <tr class="borrowing-row" data-id="<?= $b['id'] ?>" data-status="<?= $b['status'] ?>" data-approval="<?= $b['approval_status'] ?>">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 borrowing-checkbox" value="<?= $b['id'] ?>">
                                        <span class="fw-bold">#<?= $b['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($u['name'] ?? 'Không xác định') ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($u['email'] ?? '') ?></small>
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
                                        <a href="admin.php?action=borrowing_detail&id=<?= $b['id'] ?>" 
                                           class="btn btn-sm btn-outline-primary" 
                                           data-bs-toggle="tooltip" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
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



function filterBorrowings() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.borrowing-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const userName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const matchesSearch = userName.includes(searchTerm);
        
        if (matchesSearch) {
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
    
    // Lấy dữ liệu từ bảng
    const row = document.querySelector(`.borrowing-row[data-id='${borrowingId}']`);
    let borrowing = null;
    if (row) {
        // Lấy thông tin người mượn
        const userName = row.querySelector('.fw-semibold').textContent;
        const userEmail = row.querySelector('.text-muted').textContent;
        
        // Lấy thông tin sách
        const bookCell = row.querySelectorAll('td')[2]; // Cột sách mượn
        const bookInfo = bookCell.innerHTML;
        
        // Lấy trạng thái
        const status = row.dataset.status;
        const approval = row.dataset.approval;
        
        // Lấy ngày tạo
        const createdDate = row.querySelector('.text-muted').textContent;
        
        borrowing = {
            userName: userName,
            userEmail: userEmail,
            bookInfo: bookInfo,
            status: status,
            approval: approval,
            createdDate: createdDate
        };
    }
    
    setTimeout(() => {
        document.getElementById('borrowingDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6>Thông tin người mượn</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tên:</strong></td><td>${borrowing ? borrowing.userName : 'Không xác định'}</td></tr>
                        <tr><td><strong>Email:</strong></td><td>${borrowing ? borrowing.userEmail : ''}</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6>Thông tin sách mượn</h6>
                    <div class="border rounded p-3">
                        ${borrowing ? borrowing.bookInfo : '<span class="text-muted">Không có thông tin sách</span>'}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-12">
                    <h6>Trạng thái yêu cầu</h6>
                    <div class="d-flex gap-2">
                        <span class="badge bg-${borrowing && borrowing.approval === 'pending' ? 'warning' : borrowing && borrowing.approval === 'approved' ? 'success' : 'danger'}">
                            ${borrowing && borrowing.approval === 'pending' ? 'Chờ duyệt mượn' : borrowing && borrowing.approval === 'approved' ? 'Đã duyệt mượn' : 'Từ chối mượn'}
                        </span>
                        <span class="badge bg-${borrowing && borrowing.status === 'borrowed' ? 'primary' : borrowing && borrowing.status === 'returned' ? 'success' : 'secondary'}">
                            ${borrowing && borrowing.status === 'borrowed' ? 'Đã mượn' : borrowing && borrowing.status === 'returned' ? 'Đã trả' : 'Chưa mượn'}
                        </span>
                    </div>
                    <div class="mt-2">
                        <small class="text-muted">Ngày tạo: ${borrowing ? borrowing.createdDate : ''}</small>
                    </div>
                </div>
            </div>
        `;
    }, 300);
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



// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 