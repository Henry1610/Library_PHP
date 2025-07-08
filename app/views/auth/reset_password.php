<?php include __DIR__ . '/../partials/user/header.php'; ?>
<style>
.reset-bg {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
}
.reset-card {
    background: #fff;
    padding: 40px 32px 32px 32px;
    border-radius: 18px;
    box-shadow: 0 4px 24px rgba(0,0,0,0.10);
    max-width: 380px;
    width: 100%;
    text-align: center;
    animation: fadeIn 0.7s;
}
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: none; }
}
.reset-card h2 {
    color: #007bff;
    margin-bottom: 24px;
    font-size: 2rem;
    font-weight: 700;
}
.reset-card p {
    color: #666;
    margin-bottom: 24px;
    line-height: 1.6;
}
.reset-card label {
    display: block;
    text-align: left;
    font-weight: 500;
    margin-bottom: 6px;
    color: #222d32;
}
.reset-card input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 1.08rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.reset-card input:focus {
    border: 1.5px solid #007bff;
    background: #fff;
    outline: none;
}
.reset-card button {
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
.reset-card button:hover {
    background: linear-gradient(90deg,#007bff,#36d1c4);
}
.reset-card .back-link {
    margin-top: 18px;
    display: block;
    color: #007bff;
    font-weight: 500;
    text-decoration: none;
    transition: text-decoration 0.18s;
}
.reset-card .back-link:hover {
    text-decoration: underline;
}
.reset-card .error {
    color: #e74c3c;
    margin-bottom: 16px;
    font-weight: 500;
}
.reset-card .success {
    color: #27ae60;
    margin-bottom: 16px;
    font-weight: 500;
}
</style>
<div class="reset-bg">
    <div class="reset-card">
        <h2>Đặt lại mật khẩu</h2>
        <p>Nhập mật khẩu mới cho tài khoản của bạn</p>
        
        <?php if (!empty($error)) echo '<div class="error">' . $error . '</div>'; ?>
        <?php if (!empty($success)) echo '<div class="success">' . $success . '</div>'; ?>
        
        <form method="post" action="index.php?action=resetPassword">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">
            
            <label for="password">Mật khẩu mới:</label>
            <input type="password" id="password" name="password" required minlength="6">
            
            <label for="confirm_password">Xác nhận mật khẩu:</label>
            <input type="password" id="confirm_password" name="confirm_password" required minlength="6">
            
            <button type="submit">Đặt lại mật khẩu</button>
        </form>
        
        <a href="index.php?action=login" class="back-link">← Quay lại đăng nhập</a>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 