<?php 
$activeSidebar = 'users'; 
$pageTitle = 'Quản Lý Người Dùng - Admin E-Library';
include __DIR__ . '/../../partials/admin/header.php'; 
?>



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
        <div class="card border-0 shadow-sm rounded-4">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-primary">
          <tr class="text-nowrap">
            <th scope="col">
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="selectAll">
                <label class="form-check-label" for="selectAll">ID</label>
              </div>
            </th>
            <th>Thông tin</th>
            <th>Liên hệ</th>
            <th>Vai trò</th>
            <th>Trạng thái</th>
            <th>Ngày tạo</th>
            <th class="text-center">Hành động</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user): ?>
          <tr>
            <td>
              <div class="form-check">
                <input class="form-check-input user-checkbox" type="checkbox" value="<?= $user['id'] ?>" id="user<?= $user['id'] ?>">
                <label class="form-check-label fw-semibold" for="user<?= $user['id'] ?>">#<?= $user['id'] ?></label>
              </div>
            </td>
            <td>
              <div class="fw-semibold text-dark"><?= htmlspecialchars($user['name']) ?></div>
              <div class="text-muted small"><?= htmlspecialchars($user['email']) ?></div>
            </td>
            <td>
              <div class="d-flex flex-column">
                <span><i class="bi bi-telephone  me-1 smal"></i><?= htmlspecialchars($user['phone'] ?: 'Chưa có') ?></span>
                <span class="text-truncate text-muted small" style="max-width: 220px;" title="<?= htmlspecialchars($user['address']) ?>">
                  <i class="bi bi-geo-alt  me-1 small"></i><?= htmlspecialchars($user['address'] ?: 'Chưa cập nhật') ?>
                </span>
              </div>
            </td>
            <td>
              <form method="post" action="admin.php?action=update_user_role">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <select name="role" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                  <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                  <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
              </form>
            </td>
            <td>
              <form method="post" action="admin.php?action=update_user_status">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <select name="status" class="form-select form-select-sm rounded-pill" onchange="this.form.submit()">
                  <option value="active" <?= ($user['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                  <option value="locked" <?= ($user['status'] ?? 'active') === 'locked' ? 'selected' : '' ?>>Khóa</option>
                </select>
              </form>
            </td>
            <td><small class="text-muted"><?= date('d/m/Y', strtotime($user['created_at'] ?? 'now')) ?></small></td>
            <td class="text-center">
              <button type="button" class="btn btn-outline-danger btn-sm rounded-circle"
                      data-bs-toggle="tooltip" title="Xóa"
                      onclick="deleteUser(<?= $user['id'] ?>, '<?= htmlspecialchars($user['name']) ?>')">
                <i class="bi bi-trash"></i>
              </button>
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