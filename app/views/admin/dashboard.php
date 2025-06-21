<?php include __DIR__ . '/../partials/admin/header.php'; ?>
<h2>Chào mừng, <?php echo htmlspecialchars($_SESSION['user']['name']); ?> (Admin)</h2>
<a href="index.php?action=logout">Đăng xuất</a>
<h3>Quản lý hệ thống</h3>
<div style="margin-bottom:20px;">
    <a href="index.php?action=books" style="padding:10px 20px; background:#007bff; color:#fff; text-decoration:none; border-radius:5px; margin-right:10px;">Quản lý Sách</a>
    <a href="index.php?action=categories" style="padding:10px 20px; background:#28a745; color:#fff; text-decoration:none; border-radius:5px;">Quản lý Danh mục</a>
</div>
<p>Chọn chức năng bên trên để thực hiện các thao tác thêm, sửa, xóa sách hoặc danh mục.</p>
<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 