<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
</head>
<body>
    <h2>Đăng nhập</h2>
    <?php if (!empty($error)) echo '<p style="color:red">' . $error . '</p>'; ?>
    <form method="post" action="index.php?action=login">
        <label>Email:</label><br>
        <input type="email" name="email" required><br>
        <label>Mật khẩu:</label><br>
        <input type="password" name="password" required><br><br>
        <button type="submit">Đăng nhập</button>
    </form>
    <p>Chưa có tài khoản? <a href="index.php?action=register">Đăng ký</a></p>
</body>
</html> 