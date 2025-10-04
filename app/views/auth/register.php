<?php 
$pageTitle = 'Đăng Ký - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<style>
.register-bg {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
}
.register-card {
    background: #fff;
    padding: 28px 24px 24px 24px;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    max-width: 480px;
    width: 100%;
    text-align: center;
    animation: fadeIn 0.7s;
    max-height: 95vh;
    overflow-y: auto;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: none; }
}
.register-card h2 {
    color: #007bff;
    margin-bottom: 18px;
    font-size: 2rem;
    font-weight: 700;
}
.register-card label {
    display: block;
    text-align: left;
    font-weight: 500;
    margin-bottom: 4px;
    color: #222d32;
}
.register-card input[type="text"],
.register-card input[type="email"],
.register-card input[type="password"] {
    width: 100%;
    padding: 10px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 12px;
    font-size: 1.08rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.register-card input:focus {
    border: 1.5px solid #007bff;
    background: #fff;
    outline: none;
}
.register-card button {
    width: 100%;
    background: linear-gradient(90deg,#36d1c4,#007bff);
    color: #fff;
    border: none;
    padding: 12px 0;
    border-radius: 22px;
    font-weight: 600;
    font-size: 1.1rem;
    box-shadow: 0 2px 8px rgba(54,209,196,0.08);
    transition: background 0.2s;
    margin-top: 8px;
    cursor: pointer;
    letter-spacing: 1px;
}
.register-card button:hover {
    background: linear-gradient(90deg,#007bff,#36d1c4);
}
.register-card .login-link {
    margin-top: 14px;
    display: block;
    color: #007bff;
    font-weight: 500;
    text-decoration: none;
    transition: text-decoration 0.18s;
}
.register-card .login-link:hover {
    text-decoration: underline;
}
.register-card .error {
    color: #e74c3c;
    margin-bottom: 12px;
    font-weight: 500;
}
</style>
<div class="register-bg">
    <div class="register-card">
        <h2>Đăng ký</h2>
        <?php if (!empty($error)) echo '<div class="error">' . $error . '</div>'; ?>
        <form method="post" action="index.php?action=register">
            <label for="name">Họ tên:</label>
            <input type="text" id="name" name="name" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required>
            <label for="phone">Số điện thoại:</label>
            <input type="text" id="phone" name="phone">
            <label for="address">Địa chỉ:</label>
            <input type="text" id="address" name="address">
            <button type="submit">Đăng ký</button>
        </form>
        <a href="index.php?action=login" class="login-link">Đã có tài khoản? Đăng nhập</a>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 