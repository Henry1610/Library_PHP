<?php 
$pageTitle = 'Trợ Giúp - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<div class="container py-4">
    <h2 class="mb-4 text-center">Trợ giúp & Câu hỏi thường gặp</h2>
    <div class="accordion" id="faqAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq1">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    Làm thế nào để mượn sách?
                </button>
            </h2>
            <div id="collapse1" class="accordion-collapse collapse show" aria-labelledby="faq1" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Bạn cần đăng nhập tài khoản, sau đó chọn sách muốn mượn và nhấn nút "Mượn". Sách sẽ được thêm vào giỏ mượn, bạn xác nhận để gửi yêu cầu mượn.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq2">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2" aria-expanded="false" aria-controls="collapse2">
                    Làm sao biết yêu cầu mượn đã được duyệt?
                </button>
            </h2>
            <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="faq2" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Sau khi gửi yêu cầu, bạn có thể kiểm tra trạng thái trong mục "Lịch sử mượn sách". Khi admin duyệt, trạng thái sẽ chuyển sang "Đã duyệt".
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq3">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3" aria-expanded="false" aria-controls="collapse3">
                    Tôi quên mật khẩu, phải làm sao?
                </button>
            </h2>
            <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="faq3" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Hãy liên hệ quản trị viên qua email <a href="mailto:info@thuvien.com">info@thuvien.com</a> để được hỗ trợ cấp lại mật khẩu.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq4">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                    Làm thế nào để liên hệ thư viện?
                </button>
            </h2>
            <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="faq4" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Bạn có thể gửi thông tin qua trang <a href="index.php?action=contact">Liên hệ</a> hoặc gọi số 0123 456 789.
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header" id="faq5">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                    Tôi có thể trả sách muộn không?
                </button>
            </h2>
            <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="faq5" data-bs-parent="#faqAccordion">
                <div class="accordion-body">
                    Nếu trả sách muộn, bạn có thể bị tính phí phạt theo quy định của thư viện. Hãy liên hệ để biết thêm chi tiết.
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 