<?php 
$pageTitle = 'Danh Sách Yêu Thích - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
<style>
body, .container, .card, .form-control, .btn, h1, h2, h3, h4, h5, h6 {
  font-family: 'Montserrat', Arial, sans-serif !important;
}
.wishlist-header {
  background: linear-gradient(135deg, #ff6b6b, #ff8e8e);
  color: white;
  padding: 2rem 0;
  border-radius: 1rem;
  margin-bottom: 2rem;
}
.wishlist-card {
  border-radius: 1.5rem !important;
  box-shadow: 0 4px 24px rgba(255,107,107,0.08);
  transition: box-shadow 0.2s, transform 0.2s;
  overflow: hidden;
  background: #fff;
  height: 320px;
  display: flex;
  flex-direction: column;
  position: relative;
}
.wishlist-card:hover {
  box-shadow: 0 12px 36px rgba(255,107,107,0.16);
  transform: translateY(-4px) scale(1.025);
}
.wishlist-card .book-cover-img {
  border-radius: 1.5rem 1.5rem 0 0 !important;
  transition: filter 0.2s;
  width: 100%;
  aspect-ratio: 3/4;
  object-fit: cover;
  min-height: 180px;
  max-height: 320px;
  background: #f8fafd;
  display: block;
}
.wishlist-card:hover .book-cover-img {
  filter: brightness(0.93);
}
.wishlist-card .card-body {
  flex: 1 1 auto;
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  min-height: 0;
}
.wishlist-card .card-title {
  font-size: 1.08rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
  margin-bottom: 0.5rem;
}
.wishlist-card .btn-remove {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(255,255,255,0.9);
  border: none;
  border-radius: 50%;
  width: 35px;
  height: 35px;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #dc3545;
  font-size: 1.1rem;
  transition: all 0.2s;
  opacity: 0;
}
.wishlist-card:hover .btn-remove {
  opacity: 1;
}
.wishlist-card .btn-remove:hover {
  background: #dc3545;
  color: white;
  transform: scale(1.1);
}
.badge-price {
  background: linear-gradient(90deg,#ffb347,#ffcc33);
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
  border-radius: 2rem;
  padding: 0.5em 1.5em;
  box-shadow: 0 2px 8px rgba(255,200,80,0.10);
  height: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 0;
}
.badge-wishlist {
  background: linear-gradient(90deg,#ff6b6b,#ff8e8e);
  color: #fff;
  font-size: 0.9rem;
  font-weight: 600;
  border-radius: 1.2em;
  padding: 0.3em 0.8em;
  box-shadow: 0 2px 8px rgba(255,107,107,0.10);
}
.empty-wishlist {
  text-align: center;
  padding: 4rem 2rem;
  color: #6c757d;
}
.empty-wishlist i {
  font-size: 4rem;
  color: #ff6b6b;
  margin-bottom: 1rem;
}
.btn-custom-wishlist {
  width: 120px;
  min-width: 110px;
  font-weight: 600;
  font-size: 1rem;
  border-radius: 2rem;
  padding: 0.5em 1.5em;
  box-shadow: 0 2px 8px rgba(80,120,255,0.10);
  transition: background 0.2s, color 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  height: 40px;
  margin-bottom: 0;
}
.btn-custom-wishlist.btn-success {
  background: linear-gradient(90deg,#4f8cff,#6dd5ed);
  color: #fff;
  border: none;
}
.btn-custom-wishlist.btn-success:hover {
  background: linear-gradient(90deg,#6dd5ed,#4f8cff);
  color: #fff;
}
.btn-custom-wishlist.btn-secondary {
  background: #adb5bd;
  color: #fff;
  border: none;
  opacity: 0.85;
}
.btn-custom-wishlist.btn-secondary:disabled {
  background: #adb5bd;
  color: #fff;
  opacity: 0.65;
}
.wishlist-card .wishlist-action-row {
  min-width: 220px;
  min-height: 48px;
  height: 48px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 1rem;
  gap: 0.5rem;
}
@media (max-width: 767px) {
  .wishlist-card {height: 240px;}
  .wishlist-card .book-cover-img {min-height: 120px; max-height: 200px;}
}
</style>

<div class="container py-4">
    <!-- Header -->
    <div class="wishlist-header text-center">
        <h1 class="mb-2">
            <i class="fa-solid fa-heart me-3"></i>Danh sách yêu thích
        </h1>
        <p class="mb-0">Những cuốn sách bạn đã thêm vào danh sách yêu thích</p>
    </div>

    <?php if (empty($wishlist)): ?>
        <!-- Empty state -->
        <div class="empty-wishlist">
            <i class="fa-solid fa-heart-broken"></i>
            <h3>Danh sách yêu thích trống</h3>
            <p class="mb-4">Bạn chưa có cuốn sách nào trong danh sách yêu thích.</p>
            <a href="index.php?action=books" class="btn btn-primary btn-lg">
                <i class="fa-solid fa-book me-2"></i>Khám phá sách
            </a>
        </div>
    <?php else: ?>
        <!-- Wishlist content -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">
                <i class="fa-solid fa-heart text-danger me-2"></i>
                <?= count($wishlist) ?> cuốn sách yêu thích
            </h4>
            <div class="d-flex gap-2">
                <a href="index.php?action=books" class="btn btn-outline-primary">
                    <i class="fa-solid fa-plus me-2"></i>Thêm sách
                </a>
                <button class="btn btn-outline-danger" onclick="clearWishlist()">
                    <i class="fa-solid fa-trash me-2"></i>Xóa tất cả
                </button>
            </div>
        </div>

        <!-- Wishlist grid -->
        <div class="row g-4">
            <?php foreach ($wishlist as $item): ?>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card wishlist-card h-100 position-relative">
                    <button class="btn-remove" onclick="removeFromWishlist(<?= $item['book_id'] ?>)">
                        <i class="fa-solid fa-times"></i>
                    </button>
                    
                    <?php if (!empty($item['cover_img'])): ?>
                        <img src="<?= htmlspecialchars($item['cover_img']) ?>" alt="cover" class="book-cover-img">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center bg-light book-cover-img">
                            <span class="text-muted">Không có ảnh</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-body d-flex flex-column p-3">
                        <h5 class="card-title mb-2" title="<?= htmlspecialchars($item['title']) ?>">
                            <a href="index.php?action=book_detail&id=<?= $item['book_id'] ?>" class="text-decoration-none">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </h5>
                        
                        <div class="mb-2">
                            <small class="text-muted">Tác giả:</small> 
                            <?= htmlspecialchars($item['author']) ?>
                        </div>
                        
                        <div class="wishlist-action-row">
                            <span class="badge badge-price mb-0"><?= number_format($item['price'], 0) ?> đ</span>
                            <?php if ($item['available'] > 0): ?>
                                <a href="index.php?action=book_detail&id=<?= $item['book_id'] ?>" class="btn btn-success btn-custom-wishlist ms-2">
                                    <i class="fa-solid fa-bookmark-plus me-2"></i>Mượn 
                                </a>
                            <?php else: ?>
                                <button class="btn btn-secondary btn-custom-wishlist ms-2" disabled>
                                    <i class="fa-solid fa-times me-2"></i>Hết sách
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function removeFromWishlist(bookId) {
    if (confirm('Bạn có chắc muốn xóa sách này khỏi danh sách yêu thích?')) {
        fetch('index.php?action=remove_from_wishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'book_id=' + bookId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa sách');
        });
    }
}

function clearWishlist() {
    if (confirm('Bạn có chắc muốn xóa tất cả sách khỏi danh sách yêu thích?')) {
        window.location.href = 'index.php?action=clear_wishlist';
    }
}
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 