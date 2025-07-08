<?php $activeSidebar = 'categories'; include __DIR__ . '/../../partials/admin/header.php'; ?>

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
.category-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
    font-weight: 600;
}
</style>

<div class="admin-layout">
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-tags me-2"></i>Quản lý Danh mục
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-primary" onclick="exportCategories()">
                    <i class="fas fa-download me-2"></i>Xuất Excel
                </button>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i>Thêm danh mục
                </button>
            </div>
        </div>

        <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên danh mục...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="sortFilter">
                            <option value="name">Sắp xếp theo tên</option>
                            <option value="id">Sắp xếp theo ID</option>
                            <option value="books">Sắp xếp theo số sách</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
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
                                <th class="border-0">Danh mục</th>
                                <th class="border-0">Mô tả</th>
                                <th class="border-0">Số sách</th>
                                <th class="border-0">Ngày tạo</th>
                                <th class="border-0 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $cat): ?>
                            <tr class="category-row">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 category-checkbox" value="<?= $cat['id'] ?>">
                                        <span class="fw-bold">#<?= $cat['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <div class="category-icon me-3">
                                            <?= strtoupper(substr($cat['name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($cat['name']) ?></div>
                                            <small class="text-muted">Danh mục sách</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="text-truncate" style="max-width: 300px;" title="<?= htmlspecialchars($cat['description']) ?>">
                                        <?= htmlspecialchars($cat['description'] ?: 'Không có mô tả') ?>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-info">
                                        <i class="fas fa-book me-1"></i>
                                        <?= $cat['book_count'] ?? 0 ?> sách
                                    </span>
                                </td>
                                <td class="align-middle">
                                    <small class="text-muted">
                                        <?= date('d/m/Y', strtotime($cat['created_at'] ?? 'now')) ?>
                                    </small>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="Xem chi tiết"
                                                onclick="viewCategory(<?= $cat['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="tooltip" title="Chỉnh sửa"
                                                onclick="editCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>', '<?= htmlspecialchars($cat['description']) ?>')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Xóa"
                                                onclick="deleteCategory(<?= $cat['id'] ?>, '<?= htmlspecialchars($cat['name']) ?>')">
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
                Hiển thị <span id="showingCount"><?= count($categories) ?></span> trong tổng số <span id="totalCount"><?= count($categories) ?></span> danh mục
            </div>
            <nav aria-label="Category pagination">
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

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Thêm danh mục mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="admin.php?action=add_category">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên danh mục *</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Mô tả về danh mục này..."></textarea>
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

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa danh mục
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="admin.php?action=edit_category" id="editCategoryForm">
                <div class="modal-body">
                    <input type="hidden" name="category_id" id="editCategoryId">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Tên danh mục *</label>
                            <input type="text" class="form-control" name="name" id="editCategoryName" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" id="editCategoryDescription" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Category Detail Modal -->
<div class="modal fade" id="categoryDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tag me-2"></i>Chi tiết danh mục
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="categoryDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterCategories();
});

document.getElementById('sortFilter').addEventListener('change', function() {
    filterCategories();
});

function filterCategories() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const sortFilter = document.getElementById('sortFilter').value;
    const rows = document.querySelectorAll('.category-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const categoryName = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const matchesSearch = categoryName.includes(searchTerm);
        
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
    document.getElementById('sortFilter').value = 'name';
    filterCategories();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.category-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

function viewCategory(categoryId) {
    const modal = new bootstrap.Modal(document.getElementById('categoryDetailModal'));
    document.getElementById('categoryDetailContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    
    // Simulate loading category details
    setTimeout(() => {
        document.getElementById('categoryDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-4 text-center">
                    <div class="category-icon mx-auto mb-3">
                        <span>C</span>
                    </div>
                    <h5>Category Name</h5>
                    <span class="badge bg-info">15 sách</span>
                </div>
                <div class="col-md-8">
                    <h6>Thông tin danh mục</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tên:</strong></td><td>Category Name</td></tr>
                        <tr><td><strong>Mô tả:</strong></td><td>Category description goes here...</td></tr>
                        <tr><td><strong>Số sách:</strong></td><td>15</td></tr>
                        <tr><td><strong>Ngày tạo:</strong></td><td>01/01/2024</td></tr>
                    </table>
                </div>
            </div>
        `;
    }, 1000);
}

function editCategory(categoryId, categoryName, categoryDescription) {
    document.getElementById('editCategoryId').value = categoryId;
    document.getElementById('editCategoryName').value = categoryName;
    document.getElementById('editCategoryDescription').value = categoryDescription;
    
    const modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
    modal.show();
}

function deleteCategory(categoryId, categoryName) {
    if (confirm(`Bạn có chắc chắn muốn xóa danh mục "${categoryName}"?`)) {
        window.location.href = `admin.php?action=delete_category&id=${categoryId}`;
    }
}

function exportCategories() {
    alert('Chức năng xuất Excel sẽ được thêm sau');
}

// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../../partials/admin/footer.php'; ?> 