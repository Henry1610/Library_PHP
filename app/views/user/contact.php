<?php include __DIR__ . '/../partials/user/header.php'; ?>
<div class="container py-4">
    <h2 class="mb-4 text-center">Liên hệ với Thư viện</h2>
    <div class="row g-4">
        <div class="col-md-6">
            <form class="bg-light rounded shadow-sm p-4" method="post" action="">
                <div class="mb-3">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-control" name="name" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nội dung</label>
                    <textarea class="form-control" name="message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Gửi liên hệ</button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="bg-white rounded shadow-sm p-4 h-100">
                <h5>Thông tin liên hệ</h5>
                <p><i class="bi bi-geo-alt"></i> 123 Đường Sách, Quận 1, TP.HCM</p>
                <p><i class="bi bi-telephone"></i> 0123 456 789</p>
                <p><i class="bi bi-envelope"></i> <a href="mailto:info@thuvien.com">info@thuvien.com</a></p>
                <p><i class="bi bi-clock"></i> Giờ mở cửa: 8:00 - 20:00 (T2 - CN)</p>
                <div class="ratio ratio-16x9 rounded overflow-hidden mt-3">
                    <iframe src="https://www.google.com/maps?q=10.7769,106.7009&z=15&output=embed" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 