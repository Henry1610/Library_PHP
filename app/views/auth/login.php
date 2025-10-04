<?php 
$pageTitle = 'Đăng Nhập - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<style>
.login-bg {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(120deg, #e0eafc 0%, #cfdef3 100%);
}
.login-card {
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
.login-card h2 {
    color: #007bff;
    margin-bottom: 24px;
    font-size: 2rem;
    font-weight: 700;
}
.login-card label {
    display: block;
    text-align: left;
    font-weight: 500;
    margin-bottom: 6px;
    color: #222d32;
}
.login-card input[type="email"],
.login-card input[type="password"] {
    width: 100%;
    padding: 12px 14px;
    border: 1.5px solid #e0e0e0;
    border-radius: 8px;
    margin-bottom: 18px;
    font-size: 1.08rem;
    background: #f8fafc;
    transition: border 0.18s;
}
.login-card input:focus {
    border: 1.5px solid #007bff;
    background: #fff;
    outline: none;
}
.login-card button {
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
.login-card button:hover {
    background: linear-gradient(90deg,#007bff,#36d1c4);
}
.login-card .register-link {
    margin-top: 18px;
    display: block;
    color: #007bff;
    font-weight: 500;
    text-decoration: none;
    transition: text-decoration 0.18s;
}
.login-card .register-link:hover {
    text-decoration: underline;
}
.login-card .error {
    color: #e74c3c;
    margin-bottom: 16px;
    font-weight: 500;
}
.login-card .success {
    color: #28a745;
    margin-bottom: 16px;
    font-weight: 500;
    background: #d4edda;
    border: 1px solid #c3e6cb;
    padding: 10px;
    border-radius: 5px;
}
.login-card .remember-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 10px;
    margin-top: -10px;
}
.login-card .remember-row label {
    margin-bottom: 0;
    margin-left: 8px;
    font-weight: 400;
    color: #444;
    font-size: 1rem;
}
.login-card .forgot-link {
    color: #007bff;
    font-weight: 500;
    text-decoration: none;
    font-size: 0.9rem;
    transition: text-decoration 0.18s;
}
.login-card .forgot-link:hover {
    text-decoration: underline;
}
</style>
<script>
function toggleRemember(cb) {
    var form = document.getElementById('login-form');
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    
    // Lưu trạng thái checkbox vào localStorage
    localStorage.setItem('rememberLogin', cb.checked);
    
    if(cb.checked) {
        // Cho phép lưu đăng nhập
        form.setAttribute('autocomplete', 'on');
        emailInput.setAttribute('autocomplete', 'username');
        passwordInput.setAttribute('autocomplete', 'current-password');
        emailInput.setAttribute('name', 'email');
        passwordInput.setAttribute('name', 'password');
    } else {
        // Ngăn lưu đăng nhập
        form.setAttribute('autocomplete', 'off');
        emailInput.setAttribute('autocomplete', 'off');
        passwordInput.setAttribute('autocomplete', 'off');
        // Thay đổi name tạm thời để ngăn trình duyệt nhận diện
        emailInput.setAttribute('name', 'email_' + Date.now());
        passwordInput.setAttribute('name', 'password_' + Date.now());
    }
}

// Khi trang load, đảm bảo form không tự động điền
window.onload = function() {
    var form = document.getElementById('login-form');
    var emailInput = document.getElementById('email');
    var passwordInput = document.getElementById('password');
    var rememberCheckbox = document.getElementById('remember');
    
    // Khôi phục trạng thái checkbox từ localStorage
    var savedRemember = localStorage.getItem('rememberLogin');
    if (savedRemember === 'true') {
        rememberCheckbox.checked = true;
        // Áp dụng cài đặt cho phép lưu đăng nhập
        form.setAttribute('autocomplete', 'on');
        emailInput.setAttribute('autocomplete', 'username');
        passwordInput.setAttribute('autocomplete', 'current-password');
        emailInput.setAttribute('name', 'email');
        passwordInput.setAttribute('name', 'password');
    } else {
        // Áp dụng cài đặt ngăn lưu đăng nhập
        form.setAttribute('autocomplete', 'off');
        emailInput.setAttribute('autocomplete', 'off');
        passwordInput.setAttribute('autocomplete', 'off');
        
        // Thêm input ẩn để đánh lừa trình duyệt
        var fakeEmail = document.createElement('input');
        fakeEmail.type = 'email';
        fakeEmail.style.display = 'none';
        fakeEmail.setAttribute('autocomplete', 'username');
        form.appendChild(fakeEmail);
        
        var fakePassword = document.createElement('input');
        fakePassword.type = 'password';
        fakePassword.style.display = 'none';
        fakePassword.setAttribute('autocomplete', 'current-password');
        form.appendChild(fakePassword);
    }
}
</script>
<div class="login-bg">
    <div class="login-card">
        <h2>Đăng nhập</h2>
        <?php if (!empty($error)) echo '<div class="error">' . $error . '</div>'; ?>
        <?php if (!empty($success_message)) echo '<div class="success">' . $success_message . '</div>'; ?>
        <form method="post" action="index.php?action=login" id="login-form" autocomplete="off">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required autocomplete="off">
            <label for="password">Mật khẩu:</label>
            <input type="password" id="password" name="password" required autocomplete="off">
            <div class="remember-row">
                <div style="display: flex; align-items: center;">
                    <input type="checkbox" id="remember" name="remember" onclick="toggleRemember(this)">
                    <label for="remember">Lưu đăng nhập</label>
                </div>
                <a href="index.php?action=showForgotPassword" class="forgot-link">Quên mật khẩu?</a>
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
        <a href="index.php?action=register" class="register-link">Chưa có tài khoản? Đăng ký ngay</a>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 