<?php
require_once __DIR__ . '/env.php';

// VNPay Configuration
$defaultBase = rtrim($_ENV['BASE_URL'] ?? 'http://localhost/CD_PHP', '/');

return [
    'vnp_TmnCode' => $_ENV['VNP_TMN_CODE'] ?? 'PX2DIOF7', // Terminal ID / Mã Website
    'vnp_HashSecret' => $_ENV['VNP_HASH_SECRET'] ?? '19A2ZLVXKMDZ0YIJ2DDPYAY8LPB7I8FF', // Secret Key / Chuỗi bí mật tạo checksum
    'vnp_Url' => $_ENV['VNP_URL'] ?? 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html', // URL thanh toán (sandbox)
    'vnp_ReturnUrl' => $_ENV['VNP_RETURN_URL'] ?? ($defaultBase . '/public/vnpay_return.php'), // URL callback chính thức
    'vnp_IpAddr' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
    'vnp_TxnRef' => '', // Sẽ được tạo động
    'vnp_OrderInfo' => 'Thanh toan muon sach', // Thông tin đơn hàng
    'vnp_OrderType' => 'billpayment', // Loại hàng hóa
    'vnp_Amount' => '', // Số tiền (sẽ được tạo động)
    'vnp_Locale' => 'vn', // Ngôn ngữ
    'vnp_CurrCode' => 'VND', // Đơn vị tiền tệ
    'vnp_Version' => '2.1.0', // Phiên bản API
    'vnp_Command' => 'pay', // Lệnh thanh toán
];