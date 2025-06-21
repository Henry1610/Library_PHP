<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý Sách</title>
</head>
<body>
    <h2>Quản lý Sách</h2>
    <a href="index.php?action=admin">Về Dashboard</a> |
    <a href="index.php?action=add_book">Thêm sách mới</a>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th><th>Ảnh bìa</th><th>Tiêu đề</th><th>Tác giả</th><th>Nhà XB</th><th>Năm</th><th>Danh mục</th><th>ISBN</th><th>Số lượng</th><th>Còn lại</th><th>Hành động</th>
        </tr>
        <?php foreach ($books as $book): ?>
        <tr>
            <td><?= $book['id'] ?></td>
            <td>
                <?php if (!empty($book['cover_img'])): ?>
                    <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" style="max-width:60px;max-height:80px;">
                <?php else: ?>
                    <span>Không có ảnh</span>
                <?php endif; ?>
            </td>
            <td><?= htmlspecialchars($book['title']) ?></td>
            <td><?= htmlspecialchars($book['author']) ?></td>
            <td><?= htmlspecialchars($book['publisher']) ?></td>
            <td><?= htmlspecialchars($book['year']) ?></td>
            <td><?= htmlspecialchars($book['category_id']) ?></td>
            <td><?= htmlspecialchars($book['isbn']) ?></td>
            <td><?= $book['quantity'] ?></td>
            <td><?= $book['available'] ?></td>
            <td>
                <a href="index.php?action=edit_book&id=<?= $book['id'] ?>">Sửa</a> |
                <a href="index.php?action=delete_book&id=<?= $book['id'] ?>" onclick="return confirm('Xác nhận xóa?')">Xóa</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html> 