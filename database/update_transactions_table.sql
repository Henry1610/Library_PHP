-- Cập nhật bảng transactions để hỗ trợ VNPay
ALTER TABLE transactions 
ADD COLUMN vnpay_transaction_no VARCHAR(50) NULL,
ADD COLUMN vnpay_bank_code VARCHAR(20) NULL,
ADD COLUMN payment_date TIMESTAMP NULL;

-- Cập nhật cột method để hỗ trợ 'vnpay'
-- (Giả sử cột method đã tồn tại và có thể chứa 'vnpay') 