# Tích hợp VNPay vào hệ thống thư viện

## Tổng quan
Hệ thống đã được tích hợp VNPay để xử lý thanh toán cho việc mượn sách. Khi người dùng click vào nút "Thanh toán VNPay", hệ thống sẽ chuyển họ đến trang thanh toán của VNPay.

## Cấu hình VNPay

### Thông tin cấu hình hiện tại:
- **Terminal ID (vnp_TmnCode)**: `PX2DIOF7`
- **Secret Key (vnp_HashSecret)**: `19A2ZLVXKMDZ0YIJ2DDPYAY8LPB7I8FF`
- **URL thanh toán**: Sandbox (test)
- **URL callback**: `http://localhost/CD_PHP/public/vnpay_return.php`

### File cấu hình:
- `app/config/vnpay.php` - Chứa các thông số cấu hình VNPay

## Các file đã được tạo/cập nhật

### 1. Service VNPay
- `app/services/VNPayService.php` - Class xử lý thanh toán VNPay

### 2. Controller
- `app/controllers/BorrowingController.php` - Thêm method `createBorrowingPayment()`

### 3. Model
- `app/models/Transaction.php` - Thêm các method:
  - `updateMethod()`
  - `getById()`
  - `updateVnpayInfo()`

### 4. View
- `app/views/user/borrowing_history.php` - Cập nhật nút thanh toán

### 5. Callback
- `public/vnpay_return.php` - Xử lý callback từ VNPay

### 6. Database
- `database/update_transactions_table.sql` - SQL để cập nhật bảng transactions

## Luồng thanh toán

1. **Người dùng click "Thanh toán VNPay"** trong borrowing history
2. **Hệ thống tạo URL thanh toán** với thông tin giao dịch
3. **Chuyển hướng đến VNPay** để thanh toán
4. **VNPay xử lý thanh toán** và callback về hệ thống
5. **Hệ thống cập nhật trạng thái** giao dịch và mượn sách
6. **Hiển thị kết quả** cho người dùng

## Cài đặt

### 1. Cập nhật database
Chạy file SQL để thêm các cột cần thiết:
```sql
ALTER TABLE transactions 
ADD COLUMN vnpay_transaction_no VARCHAR(50) NULL,
ADD COLUMN vnpay_bank_code VARCHAR(20) NULL,
ADD COLUMN payment_date TIMESTAMP NULL;
```

### 2. Cập nhật URL callback
Trong file `app/config/vnpay.php`, cập nhật URL callback phù hợp với domain của bạn:
```php
'vnp_ReturnUrl' => 'http://your-domain.com/CD_PHP/public/vnpay_return.php',
```

### 3. Chuyển sang production
Khi deploy lên production, cần:
- Thay đổi URL từ sandbox sang production
- Cập nhật Terminal ID và Secret Key thật
- Cấu hình SSL cho callback URL

## Test

### Cách test:
1. Vào borrowing history và click "Thanh toán VNPay"
2. Sử dụng thẻ test trong môi trường sandbox
3. Kiểm tra kết quả trong database

### Thông tin cấu hình:
- **Thời gian timeout:** 60 phút
- **Timezone:** Asia/Ho_Chi_Minh
- **URL callback:** vnpay_return.php

## Thẻ test VNPay (Sandbox)

### Thẻ thành công:
- **Ngân hàng**: NCB
- **Số thẻ**: 9704198526191432198
- **Tên chủ thẻ**: NGUYEN VAN A
- **Ngày phát hành**: 07/15
- **OTP**: 123456

### Thẻ thất bại:
- **Ngân hàng**: NCB
- **Số thẻ**: 9704198526191432199
- **Tên chủ thẻ**: NGUYEN VAN A
- **Ngày phát hành**: 07/15
- **OTP**: 123456

## Xử lý lỗi

### Lỗi "Giao dịch đã quá thời gian chờ thanh toán"
**Nguyên nhân:**
- Thiếu tham số `vnp_ExpireDate`
- Thời gian `vnp_CreateDate` không đúng format
- Thời gian hết hạn quá ngắn

**Cách khắc phục:**
1. Đảm bảo có tham số `vnp_ExpireDate` với format `YmdHis`
2. Thời gian hết hạn nên là 15-30 phút sau thời gian tạo
3. Kiểm tra timezone của server

### Các mã lỗi VNPay:
- `00`: Thanh toán thành công
- `24`: Khách hàng hủy giao dịch
- `51`: Tài khoản không đủ số dư
- `65`: Tài khoản đã bị khóa
- `75`: Ngân hàng đang bảo trì
- `79`: Mã giao dịch không hợp lệ
- `99`: Các lỗi khác

### Log và debug:
- Kiểm tra log trong `vnpay_return.php`
- Xem thông tin giao dịch trong database
- Test với file `test_vnpay.php`

## Bảo mật

### Các biện pháp bảo mật:
1. **Xác thực chữ ký** - Kiểm tra SecureHash từ VNPay
2. **Validate dữ liệu** - Kiểm tra tính hợp lệ của dữ liệu
3. **Log giao dịch** - Lưu trữ thông tin giao dịch
4. **HTTPS** - Sử dụng SSL cho callback URL

### Lưu ý:
- Không bao giờ commit Secret Key lên git
- Sử dụng biến môi trường cho thông tin nhạy cảm
- Kiểm tra kỹ callback URL trước khi deploy 