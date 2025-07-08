<?php include __DIR__ . '/../partials/user/header.php'; ?>
<style>
.forgot-bg {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
}
.forgot-card {
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
.forgot-card h2 {
    color: #007bff;
    margin-bottom: 24px;
    font-size: 2rem;
    font-weight: 700;
}
.forgot-card p {
    color: #666;
    margin-bottom: 24px;
    line-height: 1.6;
}
.forgot-card label {
    display: block;
    text-align: left;
    font-weight: 500;
    margin-bottom: 6px;
    color: #222d32;
}
.forgot-card input[type="email"] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 1.08rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.forgot-card input:focus {
    border: 1.5px solid #007bff;
    background: #fff;
    outline: none;
}
.forgot-card button {
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
.forgot-card button:hover {
    background: linear-gradient(90deg,#007bff,#36d1c4);
}
.forgot-card .back-link {
    margin-top: 18px;
    display: block;
    color: #007bff;
    font-weight: 500;
    text-decoration: none;
    transition: text-decoration 0.18s;
}
.forgot-card .back-link:hover {
    text-decoration: underline;
}
.forgot-card .error {
    color: #e74c3c;
    margin-bottom: 16px;
    font-weight: 500;
}
.forgot-card .success {
    color: #27ae60;
    margin-bottom: 16px;
    font-weight: 500;
}
</style>
<div class="forgot-bg">
    <div class="forgot-card">
        <h2>Quên mật khẩu</h2>
        <p>Nhập email của bạn để nhận link đặt lại mật khẩu</p>
        
        <?php if (!empty($error)) echo '<div class="error">' . $error . '</div>'; ?>
        <?php if (!empty($success)) echo '<div class="success">' . $success . '</div>'; ?>
        
        <form method="post" action="index.php?action=forgotPassword">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            <button type="submit">Gửi email đặt lại mật khẩu</button>
        </form>
        
        <a href="index.php?action=login" class="back-link">← Quay lại đăng nhập</a>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 