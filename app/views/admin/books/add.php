<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Sách</title>
</head>
<body>
    <h2>Thêm Sách Mới</h2>
    <form method="post" action="index.php?action=add_book" enctype="multipart/form-data">
        <label>Tiêu đề:</label><br><input type="text" name="title" required><br>
        <label>Tác giả:</label><br><input type="text" name="author" required><br>
        <label>Nhà xuất bản:</label><br><input type="text" name="publisher"><br>
        <label>Năm:</label><br><input type="text" name="year"><br>
        <label>Danh mục:</label><br>
        <select name="category_id" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>ISBN:</label><br><input type="text" name="isbn"><br>
        <label>Ảnh bìa (upload):</label><br><input type="file" name="cover_img"><br>
        <label>Hoặc link ảnh bìa:</label><br><input type="text" name="cover_img_url"><br>
        <label>Số lượng:</label><br><input type="number" name="quantity" value="1"><br>
        <label>Còn lại:</label><br><input type="number" name="available" value="1"><br>
        <label>Giá bán:</label><br><input type="number" name="price" step="0.01" value="0"><br>
        <button type="submit">Thêm sách</button>
    </form>
    <a href="index.php?action=books">Quay lại danh sách</a>
</body>
</html> 