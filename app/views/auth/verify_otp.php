<?php require __DIR__ . '/../partials/user/header.php'; ?>

<div class="container mt-5" style="max-width: 520px;">
    <div class="card shadow-sm">
        <div class="card-body p-4">
            <h3 class="mb-3 text-center">Xác thực OTP</h3>
            <p class="text-muted text-center">Chúng tôi đã gửi mã OTP đến email của bạn. Vui lòng nhập mã để hoàn tất đăng ký.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <?php if (!empty($info)): ?>
                <div class="alert alert-info"><?php echo htmlspecialchars($info); ?></div>
            <?php endif; ?>

            <form method="POST" action="index.php?action=verify_otp">
                <div class="mb-3">
                    <label for="otp" class="form-label">Mã OTP (6 số)</label>
                    <input type="text" class="form-control" id="otp" name="otp" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" autocomplete="one-time-code" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,6)" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Xác nhận</button>
            </form>

            <div class="d-flex justify-content-between mt-3">
                <a href="index.php?action=resend_otp" class="small">Gửi lại mã</a>
                <a href="index.php?action=register" class="small">Sửa thông tin đăng ký</a>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../partials/user/footer.php'; ?>


