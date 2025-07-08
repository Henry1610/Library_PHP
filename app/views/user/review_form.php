<?php include __DIR__ . '/../partials/user/header.php'; ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-star-fill me-2"></i>
                        <?= $existingReview ? 'Cập nhật đánh giá' : 'Đánh giá sách' ?>
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Thông tin sách -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <?php if (!empty($book['cover_img'])): ?>
                                <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" class="img-fluid rounded shadow-sm">
                            <?php else: ?>
                                <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height:150px;">
                                    <span class="text-muted">Không có ảnh</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="col-md-9">
                            <h5 class="text-primary"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="text-muted mb-1">Tác giả: <?= htmlspecialchars($book['author']) ?></p>
                            <p class="text-muted mb-0">Nhà xuất bản: <?= htmlspecialchars($book['publisher']) ?></p>
                        </div>
                    </div>

                    <!-- Form đánh giá -->
                    <form method="POST" action="index.php?action=<?= $existingReview ? 'update_review' : 'add_review' ?>" id="reviewForm">
                        <?php if ($existingReview): ?>
                            <input type="hidden" name="review_id" value="<?= $existingReview['id'] ?>">
                        <?php else: ?>
                            <input type="hidden" name="book_id" value="<?= $book['id'] ?>">
                            <input type="hidden" name="borrowing_id" value="<?= $borrowing_id ?>">
                        <?php endif; ?>

                        <!-- Rating -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Đánh giá của bạn:</label>
                            <div class="rating-container">
                                <div class="stars">
                                    <?php for ($i = 5; $i >= 1; $i--): ?>
                                        <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" 
                                               <?= ($existingReview && $existingReview['rating'] == $i) ? 'checked' : '' ?>>
                                        <label for="star<?= $i ?>">
                                            <i class="bi bi-star"></i>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                                <div class="rating-text mt-2">
                                    <span id="ratingText">Chọn số sao</span>
                                </div>
                            </div>
                        </div>

                        <!-- Comment -->
                        <div class="mb-4">
                            <label for="comment" class="form-label fw-bold">Nhận xét (không bắt buộc):</label>
                            <textarea class="form-control" id="comment" name="comment" rows="4" 
                                      placeholder="Chia sẻ cảm nhận của bạn về cuốn sách này..."><?= $existingReview ? htmlspecialchars($existingReview['comment']) : '' ?></textarea>
                            <div class="form-text">Tối đa 500 ký tự</div>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i>
                                <?= $existingReview ? 'Cập nhật' : 'Gửi đánh giá' ?>
                            </button>
                            <a href="index.php?action=book_detail&id=<?= $book['id'] ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-1"></i>
                                Quay lại
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.rating-container {
    text-align: center;
}

.stars {
    display: inline-flex;
    flex-direction: row-reverse;
    gap: 0.2rem;
}

.stars input {
    display: none;
}

.stars label {
    cursor: pointer;
    font-size: 2rem;
    color: #ddd;
    transition: color 0.2s;
}

.stars label:hover,
.stars label:hover ~ label,
.stars input:checked ~ label {
    color: #ffc107;
}

.rating-text {
    font-size: 1.1rem;
    color: #666;
    font-weight: 500;
}

.card {
    border: none;
    border-radius: 1rem;
}

.card-header {
    border-radius: 1rem 1rem 0 0 !important;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 0.5rem;
    padding: 0.75rem 2rem;
    font-weight: 600;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ratingInputs = document.querySelectorAll('input[name="rating"]');
    const ratingText = document.getElementById('ratingText');
    const commentTextarea = document.getElementById('comment');
    const reviewForm = document.getElementById('reviewForm');

    // Rating text update
    const ratingTexts = {
        1: 'Rất không hài lòng',
        2: 'Không hài lòng', 
        3: 'Bình thường',
        4: 'Hài lòng',
        5: 'Rất hài lòng'
    };

    ratingInputs.forEach(input => {
        input.addEventListener('change', function() {
            const rating = this.value;
            ratingText.textContent = ratingTexts[rating] || 'Chọn số sao';
        });
    });

    // Comment character limit
    commentTextarea.addEventListener('input', function() {
        if (this.value.length > 500) {
            this.value = this.value.substring(0, 500);
        }
    });

    // Form validation
    reviewForm.addEventListener('submit', function(e) {
        const rating = document.querySelector('input[name="rating"]:checked');
        
        if (!rating) {
            e.preventDefault();
            alert('Vui lòng chọn số sao đánh giá!');
            return;
        }

        const comment = commentTextarea.value.trim();
        if (comment.length > 500) {
            e.preventDefault();
            alert('Nhận xét không được vượt quá 500 ký tự!');
            return;
        }
    });

    // Set initial rating text
    const checkedRating = document.querySelector('input[name="rating"]:checked');
    if (checkedRating) {
        ratingText.textContent = ratingTexts[checkedRating.value] || 'Chọn số sao';
    }
});
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 