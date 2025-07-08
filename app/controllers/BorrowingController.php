<?php
require_once __DIR__ . '/../models/Borrowing.php';
require_once __DIR__ . '/../models/BorrowDetail.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../services/VNPayService.php';

class BorrowingController {
    public function approveBorrowing($id) {
        $borrowingModel = new Borrowing();
        $borrowingModel->updateApprovalStatus($id, 'approved');
        // Tính tổng tiền mượn
        require_once __DIR__ . '/../models/BorrowDetail.php';
        require_once __DIR__ . '/../models/Book.php';
        $borrowDetailModel = new BorrowDetail();
        $bookModel = new Book();
        $db = new Database();
        $conn = $db->connect();
        $stmt = $conn->prepare('SELECT * FROM borrow_details WHERE borrowing_id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $details = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
        $total = 0;
        foreach ($details as $item) {
            $book = $bookModel->getById($item['book_id']);
            $borrow_date = new \DateTime($item['borrow_date']);
            $return_date = new \DateTime($item['return_date']);
            $days = $borrow_date <= $return_date ? $borrow_date->diff($return_date)->days + 1 : 1;
            $item_total = $book['price'] * $item['quantity'] * $days;
            $total += $item_total;
        }
        $transactionModel = new Transaction();
        $borrowing = $this->getBorrowingById($id);
        $transactionModel->create($borrowing['user_id'], $id, null, $total, 'cash', 'pending');
        header('Location: index.php?action=borrowings_list');
        exit;
    }
    public function confirmBorrowingPayment($id) {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->getByBorrowingId($id);
        if ($transaction) {
            $transactionModel->updateStatus($transaction['id'], 'success');
            $borrowingModel = new Borrowing();
            $borrowingModel->updateStatus($id, 'borrowed');
        }
        header('Location: index.php?action=borrowing_history');
        exit;
    }
    
    /**
     * Tạo thanh toán VNPay cho việc mượn sách
     */
    public function createBorrowingPayment($id) {
        $transactionModel = new Transaction();
        $transaction = $transactionModel->getByBorrowingId($id);
        
        if (!$transaction) {
            header('Location: index.php?action=borrowing_history');
            exit;
        }
        
        // Tính tổng tiền
        $borrowDetailModel = new BorrowDetail();
        $bookModel = new Book();
        $details = $borrowDetailModel->getByBorrowingId($id);
        $total = 0;
        foreach ($details as $item) {
            $book = $bookModel->getById($item['book_id']);
            $borrow_date = new \DateTime($item['borrow_date']);
            $return_date = new \DateTime($item['return_date']);
            $days = $borrow_date <= $return_date ? $borrow_date->diff($return_date)->days + 1 : 1;
            $item_total = $book['price'] * $item['quantity'] * $days;
            $total += $item_total;
        }
        
        // Cập nhật method thành vnpay
        $transactionModel->updateMethod($transaction['id'], 'vnpay');
        
        // Tạo URL thanh toán VNPay
        $vnpayService = new VNPayService();
        $orderInfo = "Thanh toan muon sach #" . $id;
        $paymentUrl = $vnpayService->createPaymentUrl($total, $transaction['id'], $orderInfo);
        
        // Chuyển hướng đến trang thanh toán VNPay
        header('Location: ' . $paymentUrl);
        exit;
    }
    public function approveReturn($id) {
        $borrowingModel = new Borrowing();
        $borrowingModel->updateReturnApprovalStatus($id, 'approved');
        // Giả sử hiện tại không có phạt, set luôn returned
        $borrowingModel->updateStatus($id, 'returned');
        // Nếu muốn mở rộng phạt, thêm logic tạo fines/transaction ở đây
        header('Location: index.php?action=borrowings_list');
        exit;
    }
    public function confirmReturnPayment($id) {
        $fineModel = new Fine();
        $fine = $fineModel->getByBorrowingId($id);
        $transactionModel = new Transaction();
        $transaction = $transactionModel->getByFineId($fine['id']);
        if ($transaction) {
            $transactionModel->updateStatus($transaction['id'], 'success');
            $borrowingModel = new Borrowing();
            $borrowingModel->updateStatus($id, 'returned');
        }
        header('Location: index.php?action=borrowing_detail&id=' . $id);
        exit;
    }
    public function listAll() {
        $borrowingModel = new Borrowing();
        $userModel = new User();
        $bookModel = new Book();
        $borrowDetailModel = new BorrowDetail();
        $db = new Database();
        $conn = $db->connect();
        $result = $conn->query('SELECT * FROM borrowings ORDER BY created_at DESC');
        $borrowings = $result->fetch_all(MYSQLI_ASSOC);
        require __DIR__ . '/../views/admin/borrowings.php';
    }
    private function getBorrowingById($id) {
        $db = new Database();
        $conn = $db->connect();
        $stmt = $conn->prepare('SELECT * FROM borrowings WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }
} 