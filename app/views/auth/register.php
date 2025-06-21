<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
</head>
<body>
    <h2>Đăng ký</h2>
    <?php if (!empty($error)) echo '<p style="color:red">' . $error . '</p>'; ?>
    <form method="post" action="index.php?action=register">
        <label>Họ tên:</label><br>
        <input type="text" name="name" required><br>
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Mật khẩu:</label><br>
        <input type="password" name="password" required><br>
        <label>Số điện thoại:</label><br>
        <input type="text" name="phone"><br>
        <label>Địa chỉ:</label><br>
        <input type="text" name="address"><br><br>
        <button type="submit">Đăng ký</button>
    </form>
    <p>Đã có tài khoản? <a href="index.php?action=login">Đăng nhập</a></p>
</body>
</html> 