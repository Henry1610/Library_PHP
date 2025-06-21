<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh mục</title>
</head>
<body>
    <h2>Thêm Danh mục Mới</h2>
    <form method="post" action="index.php?action=add_category">
        <label>Tên danh mục:</label><br><input type="text" name="name" required><br>
        <label>Mô tả:</label><br><input type="text" name="description"><br><br>
        <button type="submit">Thêm danh mục</button>
    </form>
    <a href="index.php?action=categories">Quay lại danh sách</a>
</body>
</html> 