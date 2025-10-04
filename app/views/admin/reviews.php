<?php include __DIR__ . '/../partials/admin/header.php'; ?>

<div class="admin-layout d-flex">
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>
    
    <div class="main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="bi bi-star-fill text-warning me-2"></i>
                    Quản lý đánh giá sách
                </h1>
            </div>

            <?php
            require_once __DIR__ . '/../../models/BookReview.php';
            require_once __DIR__ . '/../../models/Book.php';
            require_once __DIR__ . '/../../models/User.php';
            
            $bookReviewModel = new BookReview();
            $bookModel = new Book();
            $userModel = new User();
            
            // Lấy tất cả đánh giá
            $reviews = $bookReviewModel->getRecentReviews(100);
            ?>

            <!-- Thống kê -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0"><?= count($reviews) ?></h4>
                                    <small>Tổng đánh giá</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-star-fill fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">
                                        <?php
                                        $avgRating = 0;
                                        if (!empty($reviews)) {
                                            $totalRating = 0;
                                            foreach ($reviews as $review) {
                                                $totalRating += $review['rating'];
                                            }
                                            $avgRating = round($totalRating / count($reviews), 1);
                                        }
                                        echo $avgRating;
                                        ?>
                                    </h4>
                                    <small>Điểm TB</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-star-half fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">
                                        <?php
                                        $booksWithReviews = [];
                                        foreach ($reviews as $review) {
                                            $booksWithReviews[$review['book_id']] = true;
                                        }
                                        echo count($booksWithReviews);
                                        ?>
                                    </h4>
                                    <small>Sách được đánh giá</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-book fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-dark">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4 class="mb-0">
                                        <?php
                                        $usersWithReviews = [];
                                        foreach ($reviews as $review) {
                                            $usersWithReviews[$review['user_id']] = true;
                                        }
                                        echo count($usersWithReviews);
                                        ?>
                                    </h4>
                                    <small>Người đánh giá</small>
                                </div>
                                <div class="align-self-center">
                                    <i class="bi bi-people fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bảng đánh giá -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Danh sách đánh giá gần đây</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($reviews)): ?>
                        <div class="text-center py-4">
                            <i class="bi bi-chat-dots text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">Chưa có đánh giá nào</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID</th>
                                        <th>Sách</th>
                                        <th>Người đánh giá</th>
                                        <th>Đánh giá</th>
                                        <th>Nhận xét</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td>#<?= $review['id'] ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <strong><?= htmlspecialchars($review['book_title']) ?></strong>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($review['user_name']) ?></td>
                                            <td>
                                                <div class="stars-display">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="bi bi-star<?= $i <= $review['rating'] ? '-fill' : '' ?>" 
                                                           style="color: <?= $i <= $review['rating'] ? '#ffc107' : '#ddd' ?>;"></i>
                                                    <?php endfor; ?>
                                                    <span class="ms-1 fw-bold"><?= $review['rating'] ?>/5</span>
                                                </div>
                                            </td>
                                            <td>
                                                <?php if (!empty($review['comment'])): ?>
                                                    <span class="text-truncate d-inline-block" style="max-width: 200px;" 
                                                          title="<?= htmlspecialchars($review['comment']) ?>">
                                                        <?= htmlspecialchars($review['comment']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="text-muted">Không có</span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?= date('d/m/Y H:i', strtotime($review['created_at'])) ?></td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger" 
                                                        onclick="deleteReview(<?= $review['id'] ?>)">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
    </div>
</div>

<style>

.main-content {
    flex: 1;
    padding: 48px 56px;
    background: #f4f6f9;
    margin: 24px 24px 24px 0;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.07);
    min-width: 0;
}

/* Reviews specific styles */
.stars-display {
    display: inline-flex;
    gap: 0.1rem;
    align-items: center;
}

.stars-display i {
    font-size: 0.9rem;
}

.card {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 1rem 1rem 0 0 !important;
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
}

.table td {
    vertical-align: middle;
}

/* Responsive cho mobile */
@media (max-width: 900px) {
    .admin-layout { 
        flex-direction: column; 
    }
    .sidebar { 
        width: 100%; 
        flex-direction: row; 
        border-radius: 0; 
        margin: 0; 
        min-height: unset; 
    }
    .sidebar a { 
        flex: 1; 
        justify-content: center; 
        border-radius: 0; 
        padding: 14px 0; 
        font-size: 1rem; 
    }
    .main-content { 
        margin: 0; 
        border-radius: 0; 
        padding: 24px 8px; 
    }
}
</style>

<script>
function deleteReview(reviewId) {
    if (confirm('Bạn có chắc muốn xóa đánh giá này?')) {
        // Gửi request xóa đánh giá
        fetch('admin.php?action=delete_review&id=' + reviewId, {
            method: 'POST'
        })
        .then(response => response.text())
        .then(data => {
            alert('Xóa đánh giá thành công!');
            location.reload();
        })
        .catch(error => {
            alert('Có lỗi xảy ra: ' + error);
        });
    }
}
</script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Initialize Bootstrap tooltips -->
    <script>
        // Initialize all tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>

<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 