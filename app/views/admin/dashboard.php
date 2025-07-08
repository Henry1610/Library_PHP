<?php include __DIR__ . '/../partials/admin/header.php'; ?>

<div class="admin-layout d-flex">
    <?php include __DIR__ . '/../partials/admin/sidebar.php'; ?>
    <div class="main-content">
        <h2>Chào mừng, <?php echo htmlspecialchars($_SESSION['user']['name']); ?>!</h2>
        <p>Đây là trang quản trị thư viện. Sử dụng menu bên trái để quản lý sách, danh mục, và các yêu cầu mượn/trả sách.</p>
        <ul>
            <li><b>Quản lý Sách:</b> Thêm, sửa, xóa, xem danh sách sách trong thư viện.</li>
            <li><b>Quản lý Danh mục:</b> Thêm, sửa, xóa các danh mục sách.</li>
            <li><b>Quản lý Mượn/Trả:</b> Duyệt các yêu cầu mượn và trả sách của người dùng.</li>
        </ul>
    </div>
</div>
<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 