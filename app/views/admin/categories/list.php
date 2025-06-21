<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Danh mục</title>
</head>
<body>
    <h2>Quản lý Danh mục</h2>
    <?php if (!empty($error)) echo '<p style="color:red">' . $error . '</p>'; ?>
    <a href="index.php?action=admin">Về Dashboard</a> |
    <a href="index.php?action=add_category">Thêm danh mục mới</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th><th>Tên danh mục</th><th>Mô tả</th><th>Hành động</th>
        </tr>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= htmlspecialchars($cat['name']) ?></td>
            <td><?= htmlspecialchars($cat['description']) ?></td>
            <td>
                <a href="index.php?action=edit_category&id=<?= $cat['id'] ?>">Sửa</a> |
                <a href="index.php?action=delete_category&id=<?= $cat['id'] ?>" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html> 