<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh mục</title>
</head>
<body>
    <h2>Sửa Danh mục</h2>
    <form method="post" action="index.php?action=edit_category&id=<?= $category['id'] ?>">
        <label>Tên danh mục:</label><br><input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required><br>
        <label>Mô tả:</label><br><input type="text" name="description" value="<?= htmlspecialchars($category['description']) ?>"><br><br>
        <button type="submit">Cập nhật</button>
    </form>
    <a href="index.php?action=categories">Quay lại danh sách</a>
</body>
</html> 