<?php
session_start();
require_once __DIR__ . '/../app/services/VNPayService.php';
require_once __DIR__ . '/../app/models/Transaction.php';
require_once __DIR__ . '/../app/models/Borrowing.php';

$vnpayService = new VNPayService();

// Lấy dữ liệu từ VNPay
$inputData = array();
$returnData = array();
$data = $_GET;
foreach ($data as $key => $value) {
    if (substr($key, 0, 4) == "vnp_") {
        $inputData[$key] = $value;
    }
}

$vnp_SecureHash = $inputData['vnp_SecureHash'];
unset($inputData['vnp_SecureHash']);
unset($inputData['vnp_SecureHashType']);
ksort($inputData);
$i = 0;
$hashData = "";
foreach ($inputData as $key => $value) {
    if ($i == 1) {
        $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
    } else {
        $hashData = urlencode($key) . "=" . urlencode($value);
        $i = 1;
    }
}

$secureHash = hash_hmac('sha512', $hashData, '19A2ZLVXKMDZ0YIJ2DDPYAY8LPB7I8FF');

$transactionModel = new Transaction();
$borrowingModel = new Borrowing();

$vnp_TxnRef = $_GET['vnp_TxnRef']; // Mã giao dịch
$vnp_Amount = $_GET['vnp_Amount']; // Số tiền
$vnp_ResponseCode = $_GET['vnp_ResponseCode']; // Mã phản hồi
$vnp_TransactionNo = $_GET['vnp_TransactionNo']; // Mã giao dịch VNPay
$vnp_BankCode = $_GET['vnp_BankCode']; // Mã ngân hàng
$vnp_OrderInfo = $_GET['vnp_OrderInfo']; // Thông tin đơn hàng

// Kiểm tra chữ ký
if ($secureHash == $vnp_SecureHash) {
    if ($vnp_ResponseCode == "00") {
        // Thanh toán thành công
        $transaction = $transactionModel->getById($vnp_TxnRef);
        if ($transaction) {
            // Cập nhật trạng thái giao dịch
            $transactionModel->updateStatus($transaction['id'], 'success');
            
            // Cập nhật trạng thái mượn sách
            if ($transaction['borrowing_id']) {
                $borrowingModel->updateStatus($transaction['borrowing_id'], 'borrowed');
            }
            
            // Lưu thông tin giao dịch VNPay
            $transactionModel->updateVnpayInfo($transaction['id'], $vnp_TransactionNo, $vnp_BankCode);
            
            $message = "Thanh toán thành công! Mã giao dịch: " . $vnp_TransactionNo;
            $status = "success";
        } else {
            $message = "Không tìm thấy giao dịch!";
            $status = "error";
        }
    } else {
        // Thanh toán thất bại - xử lý các mã lỗi cụ thể
        $errorMessage = "Thanh toán thất bại! ";
        switch ($vnp_ResponseCode) {
            case '24':
                $errorMessage .= "Khách hàng hủy giao dịch";
                break;
            case '51':
                $errorMessage .= "Tài khoản không đủ số dư";
                break;
            case '65':
                $errorMessage .= "Tài khoản đã bị khóa";
                break;
            case '75':
                $errorMessage .= "Ngân hàng đang bảo trì";
                break;
            case '79':
                $errorMessage .= "Mã giao dịch không hợp lệ";
                break;
            case '99':
                $errorMessage .= "Các lỗi khác";
                break;
            default:
                $errorMessage .= "Mã lỗi: " . $vnp_ResponseCode;
                break;
        }
        $message = $errorMessage;
        $status = "error";
    }
} else {
    $message = "Chữ ký không hợp lệ!";
    $status = "error";
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kết quả thanh toán</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <?php if ($status === "success"): ?>
                            <div class="mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-success mb-3">Thanh toán thành công!</h3>
                            <p class="text-muted mb-4">Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi.</p>
                        <?php else: ?>
                            <div class="mb-4">
                                <i class="bi bi-x-circle-fill text-danger" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-danger mb-3">Thanh toán thất bại!</h3>
                            <p class="text-muted mb-4">Vui lòng thử lại hoặc liên hệ hỗ trợ.</p>
                        <?php endif; ?>
                        
                        <div class="alert alert-info">
                            <strong>Thông tin giao dịch:</strong><br>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Mã giao dịch:</strong> <?= htmlspecialchars($vnp_TxnRef) ?><br>
                                    <strong>Số tiền:</strong> <?= number_format($vnp_Amount / 100, 0) ?> VNĐ<br>
                                    <strong>Thông tin:</strong> <?= htmlspecialchars($vnp_OrderInfo) ?>
                                </div>
                                <div class="col-md-6">
                                    <?php if (isset($vnp_TransactionNo)): ?>
                                        <strong>Mã VNPay:</strong> <?= htmlspecialchars($vnp_TransactionNo) ?><br>
                                    <?php endif; ?>
                                    <?php if (isset($vnp_BankCode)): ?>
                                        <strong>Ngân hàng:</strong> <?= htmlspecialchars($vnp_BankCode) ?><br>
                                    <?php endif; ?>
                                    <strong>Thời gian:</strong> <?= date('d/m/Y H:i:s') ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <a href="index.php?action=borrowing_history" class="btn btn-primary">
                                <i class="bi bi-arrow-left me-2"></i>Xem lịch sử mượn sách
                            </a>
                            <a href="index.php" class="btn btn-outline-secondary">
                                <i class="bi bi-house me-2"></i>Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 