<?php
require_once __DIR__ . '/../config/vnpay.php';

class VNPayService {
    private $config;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../config/vnpay.php';
    }
    
    /**
     * Tạo URL thanh toán VNPay
     */
    public function createPaymentUrl($amount, $orderId, $orderInfo = null) {
        $vnp_TxnRef = $orderId; // Mã đơn hàng
        $vnp_OrderInfo = $orderInfo ?? $this->config['vnp_OrderInfo'];
        $vnp_Amount = $amount * 100; // VNPay yêu cầu số tiền * 100
        
        // Đặt timezone cho Việt Nam
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        
        // Tạo thời gian hiện tại và thời gian hết hạn (60 phút sau)
        $vnp_CreateDate = date('YmdHis');
        $vnp_ExpireDate = date('YmdHis', strtotime('+60 minutes'));
        
        $inputData = array(
            "vnp_Version" => $this->config['vnp_Version'],
            "vnp_TmnCode" => $this->config['vnp_TmnCode'],
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => $this->config['vnp_Command'],
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => $this->config['vnp_CurrCode'],
            "vnp_IpAddr" => $this->config['vnp_IpAddr'],
            "vnp_Locale" => $this->config['vnp_Locale'],
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $this->config['vnp_OrderType'],
            "vnp_ReturnUrl" => $this->config['vnp_ReturnUrl'],
            "vnp_TxnRef" => $vnp_TxnRef,
            "vnp_ExpireDate" => $vnp_ExpireDate,
        );
        
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }
        
        $vnp_Url = $this->config['vnp_Url'] . "?" . $query;
        if (isset($this->config['vnp_HashSecret'])) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $this->config['vnp_HashSecret']);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        
        return $vnp_Url;
    }
    
    /**
     * Xác thực callback từ VNPay
     */
    public function verifyCallback($inputData) {
        if (isset($inputData['vnp_SecureHash'])) {
            $secureHash = $inputData['vnp_SecureHash'];
            unset($inputData['vnp_SecureHash']);
            unset($inputData['vnp_SecureHashType']);
            
            ksort($inputData);
            $hashData = "";
            $i = 0;
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashData = $hashData . '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashData = urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
            }
            
            $calculatedHash = hash_hmac('sha512', $hashData, $this->config['vnp_HashSecret']);
            return $calculatedHash == $secureHash;
        }
        return false;
    }
    
    /**
     * Kiểm tra trạng thái thanh toán
     */
    public function checkPaymentStatus($responseCode) {
        switch ($responseCode) {
            case '00':
                return ['success' => true, 'message' => 'Thanh toán thành công'];
            case '24':
                return ['success' => false, 'message' => 'Khách hàng hủy giao dịch'];
            case '51':
                return ['success' => false, 'message' => 'Tài khoản không đủ số dư'];
            case '65':
                return ['success' => false, 'message' => 'Tài khoản đã bị khóa'];
            case '75':
                return ['success' => false, 'message' => 'Ngân hàng đang bảo trì'];
            case '79':
                return ['success' => false, 'message' => 'Mã giao dịch không hợp lệ'];
            case '99':
                return ['success' => false, 'message' => 'Các lỗi khác'];
            default:
                return ['success' => false, 'message' => 'Lỗi không xác định'];
        }
    }
} 