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
    max-width: 520px;
    padding: 36px 32px 28px 32px;
    border-radius: 18px;
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
.form-group {
    margin-bottom: 18px;
}
.form-card label {
    font-weight: 500;
    color: #222d32;
    margin-bottom: 6px;
    display: block;
}
.form-card input[type="text"],
.form-card input[type="number"],
.form-card input[type="file"],
.form-card select {
    width: 100%;
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1.08rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.form-card input[type="text"]:focus,
.form-card input[type="number"]:focus,
.form-card select:focus {
    border: 1.5px solid #007bff;
    outline: none;
    background: #fff;
}
.form-actions {
    display: flex;
    align-items: center;
    gap: 18px;
    margin-top: 10px;
}
.form-card button {
    background: linear-gradient(90deg,#36d1c4,#007bff);
    color: #fff;
    border: none;
    padding: 10px 32px;
    border-radius: 22px;
    font-weight: 500;
    font-size: 1.08rem;
    box-shadow: 0 2px 8px rgba(54,209,196,0.08);
    transition: background 0.2s;
    cursor: pointer;
}
.form-card button:hover { background: linear-gradient(90deg,#007bff,#36d1c4); }
.form-card .back-link {
    color: #007bff;
    text-decoration: none;
    font-weight: 500;
    font-size: 1rem;
    transition: text-decoration 0.18s;
}
.form-card .back-link:hover { text-decoration: underline; }
</style>
<div class="admin-layout">
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content center-flex">
        <div class="form-card">
            <h2>Thêm Sách Mới</h2>
            <form method="post" action="admin.php?action=add_book" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Tiêu đề:</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="form-group">
                    <label for="author">Tác giả:</label>
                    <input type="text" id="author" name="author" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Nhà xuất bản:</label>
                    <input type="text" id="publisher" name="publisher">
                </div>
                <div class="form-group">
                    <label for="year">Năm:</label>
                    <input type="text" id="year" name="year">
                </div>
                <div class="form-group">
                    <label for="category_id">Danh mục:</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">-- Chọn danh mục --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN:</label>
                    <input type="text" id="isbn" name="isbn">
                </div>
                <div class="form-group">
                    <label for="cover_img">Ảnh bìa (upload):</label>
                    <input type="file" id="cover_img" name="cover_img">
                </div>
                <div class="form-group">
                    <label for="cover_img_url">Hoặc link ảnh bìa:</label>
                    <input type="text" id="cover_img_url" name="cover_img_url">
                </div>
                <div class="form-group">
                    <label for="quantity">Số lượng:</label>
                    <input type="number" id="quantity" name="quantity" value="1">
                </div>
                <div class="form-group">
                    <label for="available">Còn lại:</label>
                    <input type="number" id="available" name="available" value="1">
                </div>
                <div class="form-group">
                    <label for="price">Giá bán:</label>
                    <input type="number" id="price" name="price" step="1" value="0">
                </div>
                <div class="form-actions">
                    <button type="submit">Thêm sách</button>
                    <a href="admin.php?action=books" class="back-link">← Quay lại danh sách</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin/footer.php'; ?> 