<?php $activeSidebar = 'books'; include __DIR__ . '/../../partials/admin/header.php'; ?>
<style>
.main-content.center-flex {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 80vh;
    padding: 0;
}
.form-card {
    background: #fff;
    width: 100%;
    max-width: 800px;
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    margin: 0;
}
.form-card h2 {
    margin-top: 0;
    color: #007bff;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 24px;
}
.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}
.form-group {
    display: flex;
    flex-direction: column;
}
.form-group img {
    max-width: 100px;
    margin-top: 8px;
}
.form-full {
    grid-column: 1 / -1;
}
.form-card label {
    font-weight: 500;
    color: #222d32;
    margin-bottom: 6px;
}
.form-card input[type="text"],
.form-card input[type="number"],
.form-card input[type="file"],
.form-card select {
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.form-card input:focus,
.form-card select:focus {
    border: 1.5px solid #007bff;
    background: #fff;
    outline: none;
}
.form-actions, .form-group.form-full {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 24px;
    grid-column: 1 / -1;
}
.form-card button {
    background: linear-gradient(90deg,#36d1c4,#007bff);
    color: #fff;
    border: none;
    padding: 10px 28px;
    border-radius: 22px;
    font-weight: 500;
    font-size: 1rem;
    cursor: pointer;
}
.form-card button:hover {
    background: linear-gradient(90deg,#007bff,#36d1c4);
}
.form-card .back-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
}
.form-card .back-link:hover {
    text-decoration: underline;
}
@media (max-width: 700px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
<div class="admin-layout">
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content center-flex">
        <div class="form-card">
            <h2>Sửa Sách</h2>
            <form method="post" action="admin.php?action=edit_book&id=<?= $book['id'] ?>" enctype="multipart/form-data">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Tiêu đề:</label>
                        <input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Tác giả:</label>
                        <input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label>Nhà xuất bản:</label>
                        <input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Năm:</label>
                        <input type="text" name="year" value="<?= htmlspecialchars($book['year']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Danh mục:</label>
                        <select name="category_id" required>
                            <option value="">-- Chọn danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $book['category_id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ISBN:</label>
                        <input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>">
                    </div>
                    <div class="form-group form-full">
                        <label>Ảnh bìa hiện tại:</label>
                        <?php if (!empty($book['cover_img'])): ?>
                            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover">
                        <?php else: ?>
                            <span>Chưa có ảnh</span>
                        <?php endif; ?>
                        <input type="hidden" name="old_cover_img" value="<?= htmlspecialchars($book['cover_img']) ?>">
                    </div>
                    <div class="form-group">
                        <label>Ảnh bìa (upload mới):</label>
                        <input type="file" name="cover_img">
                    </div>
                    <div class="form-group">
                        <label>Hoặc link ảnh bìa mới:</label>
                        <input type="text" name="cover_img_url">
                    </div>
                    <div class="form-group">
                        <label>Số lượng:</label>
                        <input type="number" name="quantity" value="<?= $book['quantity'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Còn lại:</label>
                        <input type="number" name="available" value="<?= $book['available'] ?>">
                    </div>
                    <div class="form-group">
                        <label>Giá bán:</label>
                        <input type="number" name="price" step="1" value="<?= isset($book['price']) ? (int)$book['price'] : 0 ?>">
                    </div>
                    <div class="form-group form-full">
                        <button type="submit">Cập nhật</button>
                        <a href="admin.php?action=books" class="back-link" style="margin-top: 0; display:inline-block;">Quay lại danh sách</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin/footer.php'; ?>
