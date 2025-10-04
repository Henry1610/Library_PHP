<?php 
$activeSidebar = 'books'; 
$pageTitle = 'Quản Lý Sách - Admin E-Library';
include __DIR__ . '/../../partials/admin/header.php'; 
?>

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
.book-cover {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.book-cover-placeholder {
    width: 60px;
    height: 80px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    font-weight: 600;
}
</style>

<div class="admin-layout">
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">
                <i class="fas fa-book me-2"></i>Quản lý Sách
            </h2>
            <div class="d-flex gap-2">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBookModal">
                    <i class="fas fa-plus me-2"></i>Thêm sách
                </button>
            </div>
        </div>

        <!-- Search and Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Tìm kiếm theo tên sách, tác giả, ISBN...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="categoryFilter">
                            <option value="">Tất cả danh mục</option>
                            <?php if (!empty($categories)) foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>Xóa bộ lọc
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Books Table -->
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
                                <th class="border-0">Ảnh bìa</th>
                                <th class="border-0">Thông tin sách</th>
                                <th class="border-0">Danh mục</th>
                                <th class="border-0">Số lượng</th>
                                <th class="border-0">Giá</th>
                                <th class="border-0">Trạng thái</th>
                                <th class="border-0 text-center">Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($books as $book): ?>
                            <tr class="book-row" data-id="<?= $book['id'] ?>" data-category="<?= $book['category_id'] ?>" data-status="<?= $book['available'] > 0 ? ($book['available'] < 5 ? 'low' : 'available') : 'out' ?>">
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        <input type="checkbox" class="form-check-input me-2 book-checkbox" value="<?= $book['id'] ?>">
                                        <span class="fw-bold">#<?= $book['id'] ?></span>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php if (!empty($book['cover_img'])): ?>
                                        <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="book-cover">
                                    <?php else: ?>
                                        <div class="book-cover-placeholder">
                                            <?= strtoupper(substr($book['title'], 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle">
                                    <div>
                                        <div class="fw-semibold"><?= htmlspecialchars($book['title']) ?></div>
                                        <div class="text-muted small">
                                            <i class="fas fa-user me-1"></i><?= htmlspecialchars($book['author']) ?>
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-building me-1"></i><?= htmlspecialchars($book['publisher']) ?> (<?= htmlspecialchars($book['year']) ?>)
                                        </div>
                                        <div class="text-muted small">
                                            <i class="fas fa-barcode me-1"></i><?= htmlspecialchars($book['isbn']) ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <span class="badge bg-secondary"><?= htmlspecialchars($book['category_id']) ?></span>
                                </td>
                                <td class="align-middle">
                                    <div class="text-center">
                                        <div class="fw-bold"><?= $book['quantity'] ?></div>
                                        <small class="text-muted">Tổng cộng</small>
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <div class="fw-bold text-success">
                                        <?= number_format($book['price'], 0) ?> đ
                                    </div>
                                </td>
                                <td class="align-middle">
                                    <?php
                                    $statusClass = 'bg-success';
                                    $statusText = 'Có sẵn';
                                    if ($book['available'] == 0) {
                                        $statusClass = 'bg-danger';
                                        $statusText = 'Hết sách';
                                    } elseif ($book['available'] < 5) {
                                        $statusClass = 'bg-warning';
                                        $statusText = 'Sắp hết';
                                    }
                                    ?>
                                    <span class="badge <?= $statusClass ?>">
                                        <i class="fas fa-book me-1"></i>
                                        <?= $statusText ?> (<?= $book['available'] ?>)
                                    </span>
                                </td>
                                <td class="align-middle text-center">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="tooltip" title="Xem chi tiết"
                                                onclick="viewBook(<?= $book['id'] ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-warning" 
                                                data-bs-toggle="tooltip" title="Chỉnh sửa"
                                                onclick="editBook(<?= $book['id'] ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="tooltip" title="Xóa"
                                                onclick="deleteBook(<?= $book['id'] ?>, '<?= htmlspecialchars($book['title']) ?>')">
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
                Hiển thị <span id="showingCount"><?= count($books) ?></span> trong tổng số <span id="totalCount"><?= count($books) ?></span> sách
            </div>
            <nav aria-label="Book pagination">
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

<!-- Add Book Modal -->
<div class="modal fade" id="addBookModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-plus me-2"></i>Thêm sách mới
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="admin.php?action=add_book" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label">Tên sách *</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">ISBN</label>
                            <input type="text" class="form-control" name="isbn">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tác giả *</label>
                            <input type="text" class="form-control" name="author" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nhà xuất bản</label>
                            <input type="text" class="form-control" name="publisher">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Năm xuất bản</label>
                            <input type="number" class="form-control" name="year" min="1900" max="<?= date('Y') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Danh mục</label>
                            <select class="form-select" name="category_id">
                                <option value="">Chọn danh mục</option>
                                <?php if (!empty($categories)) foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Giá (VNĐ)</label>
                            <input type="number" class="form-control" name="price" min="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Số lượng</label>
                            <input type="number" class="form-control" name="quantity" value="1" min="1">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Ảnh bìa</label>
                            <input type="file" class="form-control" name="cover_img" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Mô tả</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
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

<!-- Book Detail Modal -->
<div class="modal fade" id="bookDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-book me-2"></i>Chi tiết sách
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    filterBooks();
});

document.getElementById('categoryFilter').addEventListener('change', function() {
    filterBooks();
});

function filterBooks() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('categoryFilter').value;
    const rows = document.querySelectorAll('.book-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const title = row.querySelector('.fw-semibold').textContent.toLowerCase();
        const author = row.querySelector('.text-muted.small').textContent.toLowerCase();
        const category = row.dataset.category;
        
        const matchesSearch = title.includes(searchTerm) || author.includes(searchTerm);
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        if (matchesSearch && matchesCategory) {
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
    document.getElementById('categoryFilter').value = '';
    filterBooks();
}

// Select all functionality
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.book-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Dữ liệu categories cho JS
const CATEGORIES = <?php echo json_encode(array_column($categories ?? [], 'name', 'id')); ?>;

function viewBook(bookId) {
    const modal = new bootstrap.Modal(document.getElementById('bookDetailModal'));
    document.getElementById('bookDetailContent').innerHTML = '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    modal.show();
    // Lấy dữ liệu sách từ bảng (hoặc có thể fetch từ server nếu muốn)
    const row = document.querySelector(`.book-row[data-id='${bookId}']`);
    let book = null;
    if (row) {
        // Lấy ảnh bìa
        const coverImg = row.querySelector('.book-cover');
        const coverPlaceholder = row.querySelector('.book-cover-placeholder');
        let coverImage = '';
        if (coverImg) {
            coverImage = coverImg.src;
        } else if (coverPlaceholder) {
            coverImage = ''; // Sẽ hiển thị placeholder
        }
        
        book = {
            title: row.querySelector('.fw-semibold').textContent,
            author: row.querySelector('.fa-user').parentElement.textContent.trim(),
            publisher: row.querySelector('.fa-building').parentElement.textContent.trim(),
            year: row.querySelector('.fa-building').parentElement.textContent.match(/\((\d{4})\)/)?.[1] || '',
            isbn: row.querySelector('.fa-barcode').parentElement.textContent.trim(),
            category_id: row.getAttribute('data-category'),
            price: row.querySelector('.text-success').textContent.trim(),
            quantity: (function() {
                // Lấy số lượng từ cột số lượng (cột thứ 5)
                const quantityCell = row.querySelectorAll('td')[4]; // Cột số lượng
                if (quantityCell) {
                    const quantityDiv = quantityCell.querySelector('.fw-bold');
                    return quantityDiv ? quantityDiv.textContent.trim() : '';
                }
                return '';
            })(),
            cover_img: coverImage,
            available: (function() {
                // Lấy badge trạng thái ở cột trạng thái (badge có icon fa-book)
                const badges = row.querySelectorAll('.badge');
                for (let b of badges) {
                    if (b.querySelector('.fa-book')) {
                        const m = b.textContent.match(/\((\d+)\)/);
                        return m ? m[1] : '';
                    }
                }
                return '';
            })()
        };
    }
    setTimeout(() => {
        document.getElementById('bookDetailContent').innerHTML = `
            <div class="row">
                <div class="col-md-4 text-center">
                    ${book && book.cover_img ? 
                        `<img src="${book.cover_img}" alt="cover" class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">` :
                        `<div class="book-cover-placeholder mx-auto mb-3">
                            <span>${book ? book.title.charAt(0).toUpperCase() : 'B'}</span>
                        </div>`
                    }
                    <h5>${book ? book.title : 'Book Title'}</h5>
                    <span class="badge bg-success">Có sẵn</span>
                </div>
                <div class="col-md-8">
                    <h6>Thông tin sách</h6>
                    <table class="table table-sm">
                        <tr><td><strong>Tên sách:</strong></td><td>${book ? book.title : ''}</td></tr>
                        <tr><td><strong>Tác giả:</strong></td><td>${book ? book.author : ''}</td></tr>
                        <tr><td><strong>Nhà xuất bản:</strong></td><td>${book ? book.publisher : ''}</td></tr>
                        <tr><td><strong>Năm xuất bản:</strong></td><td>${book ? book.year : ''}</td></tr>
                        <tr><td><strong>ISBN:</strong></td><td>${book ? book.isbn : ''}</td></tr>
                        <tr><td><strong>Danh mục:</strong></td><td>${book && CATEGORIES[book.category_id] ? CATEGORIES[book.category_id] : ''}</td></tr>
                        <tr><td><strong>Giá:</strong></td><td>${book ? book.price : ''}</td></tr>
                        <tr><td><strong>Số lượng tổng:</strong></td><td>${book ? book.quantity : ''}</td></tr>
                        <tr><td><strong>Số lượng có sẵn:</strong></td><td>${book ? book.available : ''}</td></tr>
                    </table>
                </div>
            </div>
        `;
    }, 300);
}

function editBook(bookId) {
    window.location.href = `admin.php?action=edit_book&id=${bookId}`;
}

function deleteBook(bookId, bookTitle) {
    if (confirm(`Bạn có chắc chắn muốn xóa sách "${bookTitle}"?`)) {
        window.location.href = `admin.php?action=delete_book&id=${bookId}`;
    }
}



// Initialize tooltips
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
});
</script>

<?php include __DIR__ . '/../../partials/admin/footer.php'; ?> 