<?php $activeSidebar = 'categories'; include __DIR__ . '/../../partials/admin/header.php'; ?>
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
    padding: 32px;
    border-radius: 16px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08);
    font-family: Arial, sans-serif;
}
.form-card h2 {
    margin-top: 0;
    color: #007bff;
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 24px;
    text-align: center;
}
.form-group {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}
.form-group label {
    width: 130px;
    font-weight: 500;
    color: #222d32;
    margin-right: 10px;
}
.form-group input[type="text"] {
    flex: 1;
    padding: 10px 14px;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    font-size: 1rem;
    background: #f8fafc;
}
.form-group input[type="text"]:focus {
    border: 1.5px solid #007bff;
    outline: none;
    background: #fff;
}
.form-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 24px;
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
</style>


<div class="admin-layout">
    <?php include __DIR__ . '/../../partials/admin/sidebar.php'; ?>
    <div class="main-content center-flex">
        <div class="form-card">
            <h2>Sửa Danh mục</h2>
            <form method="post" action="admin.php?action=edit_category&id=<?= $category['id'] ?>">
                <div class="form-group">
                    <label for="name">Tên danh mục:</label>
                    <input type="text" id="name" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <input type="text" id="description" name="description" value="<?= htmlspecialchars($category['description']) ?>">
                </div>
                <div class="form-actions">
                    <button type="submit">Cập nhật</button>
                    <a href="admin.php?action=categories" class="back-link">← Quay lại danh sách</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../../partials/admin/footer.php'; ?>
