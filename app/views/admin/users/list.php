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
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên, email...">
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
                            <tr class="user-row" data-role="<?= $user['role'] ?>" data-status="<?= $user['status'] ?? 'active' ?>">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 user-checkbox" value="<?= $user['id'] ?>">
                                        <span class="fw-bold">#<?= $user['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                        <small class="text-muted"><?= htmlspecialchars($user['email']) ?></small>
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
                                    <form method="post" action="admin.php?action=update_user_status" class="d-inline">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <select name="status" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                            <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                            <option value="locked" <?= ($user['status'] ?? 'active') === 'locked' ? 'selected' : '' ?>>Khóa</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?>
                                    </small>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">


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





<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterUsers();
});



function filterUsers() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('.user-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const name = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const email = row.querySelector('.text-muted').textContent.toLowerCase();
        
        const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);
        
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
    filterUsers();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.user-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});





function deleteUser(userId, userName) {
    if (confirm(`Bạn có chắc chắn muốn xóa người dùng "${userName}"?`)) {
        window.location.href = `admin.php?action=delete_user&id=${userId}`;
    }
}



// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../../partials/admin/footer.php'; ?> 