<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Sách</title>
</head>
<body>
    <h2>Sửa Sách</h2>
    <form method="post" action="index.php?action=edit_book&id=<?= $book['id'] ?>" enctype="multipart/form-data">
        <label>Tiêu đề:</label><br><input type="text" name="title" value="<?= htmlspecialchars($book['title']) ?>" required><br>
        <label>Tác giả:</label><br><input type="text" name="author" value="<?= htmlspecialchars($book['author']) ?>" required><br>
        <label>Nhà xuất bản:</label><br><input type="text" name="publisher" value="<?= htmlspecialchars($book['publisher']) ?>"><br>
        <label>Năm:</label><br><input type="text" name="year" value="<?= htmlspecialchars($book['year']) ?>"><br>
        <label>Danh mục:</label><br>
        <select name="category_id" required>
            <option value="">-- Chọn danh mục --</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $book['category_id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['name']) ?></option>
            <?php endforeach; ?>
        </select><br>
        <label>ISBN:</label><br><input type="text" name="isbn" value="<?= htmlspecialchars($book['isbn']) ?>"><br>
        <label>Ảnh bìa hiện tại:</label><br>
        <?php if (!empty($book['cover_img'])): ?>
            <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" style="max-width:100px;"><br>
        <?php else: ?>
            <span>Chưa có ảnh</span><br>
        <?php endif; ?>
        <input type="hidden" name="old_cover_img" value="<?= htmlspecialchars($book['cover_img']) ?>">
        <label>Ảnh bìa (upload mới):</label><br><input type="file" name="cover_img"><br>
        <label>Hoặc link ảnh bìa mới:</label><br><input type="text" name="cover_img_url"><br>
        <label>Số lượng:</label><br><input type="number" name="quantity" value="<?= $book['quantity'] ?>"><br>
        <label>Còn lại:</label><br><input type="number" name="available" value="<?= $book['available'] ?>"><br><br>
        <button type="submit">Cập nhật</button>
    </form>
    <a href="index.php?action=books">Quay lại danh sách</a>
</body>
</html> 