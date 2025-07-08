<?php $activeSidebar = 'users'; include __DIR__ . '/../../partials/admin/header.php'; ?>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1.2rem;
    font-weight: 600;
}
.avatar-lg {
    width: 80px;
    height: 80px;
    font-size: 2rem;
    font-weight: 600;
}
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
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-users me-2"></i>Quản lý Người dùng
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="exportUsers()">
                    <i class="fas fa-download me-2"></i>Xuất Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="fas fa-plus me-2"></i>Thêm người dùng
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
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên, email...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="roleFilter">
                            <option value="">Tất cả vai trò</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active">Đang hoạt động</option>
                            <option value="inactive">Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Xóa
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
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
                                <th class="border-0">Thông tin</th>
                                <th class="border-0">Liên hệ</th>
                                <th class="border-0">Vai trò</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0">Ngày tạo</th>
                                <th class="border-0 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="user-row" data-role="<?= $user['role'] ?>" data-status="active">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 user-checkbox" value="<?= $user['id'] ?>">
                                        <span class="fw-bold">#<?= $user['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3">
                                            <?= strtoupper(substr($user['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                            <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-phone text-muted me-2"></i>
                                            <span><?= htmlspecialchars($user['phone'] ?: 'Chưa cập nhật') ?></span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                            <span class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($user['address']) ?>">
                                                <?= htmlspecialchars($user['address'] ?: 'Chưa cập nhật') ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <form method="post" action="admin.php?action=update_user_role" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="role" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-success">Đang hoạt động</span>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                                    </small>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="Xem chi tiết"
                                                onclick="viewUser(<?= $user['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="tooltip" title="Chỉnh sửa"
                                                onclick="editUser(<?= $user['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Xóa"
                                                onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')">
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
                Hiển thị <span id="showingCount"><?= count($users) ?></span> trong tổng số <span id="totalCount"><?= count($users) ?></span> người dùng
            </div>
            <nav aria-label="User pagination">
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Thêm người dùng mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="admin.php?action=add_user">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Họ và tên *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Email *</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mật khẩu *</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Vai trò</label>
                            <select class="form-select" name="role">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- User Detail Modal -->
<div class="modal fade" id="userDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>Chi tiết người dùng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterUsers();
});

document.getElementById('roleFilter').addEventListener('change', function() {
    filterUsers();
});

document.getElementById('statusFilter').addEventListener('change', function() {
    filterUsers();
});

function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('.user-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const email = row.querySelector('.text-muted').textContent.toLowerCase();
        const role = row.dataset.role;
        const status = row.dataset.status;
        
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        const matchesRole = !roleFilter || role === roleFilter;
        const matchesStatus = !statusFilter || status === statusFilter;
        
        if (matchesSearch && matchesRole && matchesStatus) {
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
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    filterUsers();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function viewUser(userId) {
    // Load user details via AJAX
    const modal = new bootstrap.Modal(document.getElementById('userDetailModal'));
    document.getElementById('userDetailContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Simulate loading user details
    setTimeout(() => {
        document.getElementById('userDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="avatar-lg bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3">
                        <span class="fs-1">U</span>
                    </div>
                    <h5>User Name</h5>
                    <span class="badge bg-success">Đang hoạt động</span>
                </div>
                <div class="col-md-8">
                    <h6>Thông tin cá nhân</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Email:</strong></td><td>user@example.com</td></tr>
                        <tr><td><strong>Số điện thoại:</strong></td><td>0123456789</td></tr>
                        <tr><td><strong>Địa chỉ:</strong></td><td>123 Đường ABC, Quận XYZ, TP.HCM</td></tr>
                        <tr><td><strong>Vai trò:</strong></td><td><span class="badge bg-primary">User</span></td></tr>
                        <tr><td><strong>Ngày tạo:</strong></td><td>01/01/2024</td></tr>
                    </table>
                </div>
            </div>
        `;
    }, 1000);
}

function editUser(userId) {
    // Implement edit functionality
    alert('Chức năng chỉnh sửa sẽ được thêm sau');
}

function deleteUser(userId, userName) {
    if (confirm(`Bạn có chắc chắn muốn xóa người dùng "${userName}"?`)) {
        window.location.href = `admin.php?action=delete_user&id=${userId}`;
    }
}

function exportUsers() {
    // Implement export functionality
    alert('Chức năng xuất Excel sẽ được thêm sau');
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../../partials/admin/footer.php'; ?> 