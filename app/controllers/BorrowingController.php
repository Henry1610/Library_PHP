<?php
require_once __DIR__ . '/../models/Borrowing.php';
require_once __DIR__ . '/../models/BorrowDetail.php';
require_once __DIR__ . '/../models/Transaction.php';
require_once __DIR__ . '/../models/Fine.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Book.php';
require_once __DIR__ . '/../services/VNPayService.php';

class BorrowingController {
    const FINE_PER_DAY = 10000; // Mức phạt mỗi ngày 
    const MAX_FINE = 500000;   // Mức phạt tối đa
    const FINE_PAYMENT_GRACE_DAYS = 7; // Số ngày cho phép thanh toán phạt trước khi khóa tài khoản

    public function approveBorrowing($id) {
        $borrowingModel = new Borrowing();
        $borrowingModel->updateApprovalStatus($id, 'approved');
        // Tính tổng tiền mượn và tăng lượt mượn
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
            
            // Tăng lượt mượn và giảm số lượng có sẵn
            $bookModel->incrementBorrowCount($item['book_id'], $item['quantity']);
            $bookModel->updateAvailable($item['book_id'], $item['quantity']);
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

    /**
     * Tạo thanh toán VNPay cho khoản phạt
     */
    public function createFinePayment($borrowing_id) {
        $fineModel = new Fine();
        $transactionModel = new Transaction();
        $vnpayService = new VNPayService();

        $fine = $fineModel->getByBorrowingId($borrowing_id);
        if (!$fine) {
            header('Location: index.php?action=borrowing_history');
            exit;
        }

        $transaction = $transactionModel->getByFineId($fine['id']);

        // Nếu chưa có transaction cho khoản phạt này, tạo mới
        if (!$transaction) {
            $borrowing = $this->getBorrowingById($borrowing_id);
            $transactionId = $transactionModel->create($borrowing['user_id'], $borrowing_id, $fine['id'], $fine['amount'], 'vnpay', 'pending');
            $transaction = $transactionModel->getById($transactionId);
        } else {
            // Cập nhật method thành vnpay nếu chưa phải
            if ($transaction['method'] !== 'vnpay') {
                $transactionModel->updateMethod($transaction['id'], 'vnpay');
                $transaction = $transactionModel->getById($transaction['id']); // Lấy lại thông tin transaction sau khi update
            }
        }

        $orderInfo = "Thanh toan tien phat muon sach #" . $borrowing_id;
        $paymentUrl = $vnpayService->createPaymentUrl($fine['amount'], $transaction['id'], $orderInfo);

        header('Location: ' . $paymentUrl);
        exit;
    }

    public function approveReturn($id) {
        $borrowingModel = new Borrowing();
        $borrowingModel->updateReturnApprovalStatus($id, 'approved');
        
        // Tăng lại số lượng có sẵn khi trả sách
        require_once __DIR__ . '/../models/BorrowDetail.php';
        require_once __DIR__ . '/../models/Book.php';
        $borrowDetailModel = new BorrowDetail();
        $bookModel = new Book();
        
        $details = $borrowDetailModel->getByBorrowingId($id);
        $borrowing = $this->getBorrowingById($id); // Lấy thông tin phiếu mượn

        $overdueFine = 0;
        $today = new \DateTime();

        foreach ($details as $item) {
            $bookModel->returnAvailable($item['book_id'], $item['quantity']);
            $return_date = new \DateTime($item['return_date']);
            
            if ($today > $return_date) {
                $interval = $today->diff($return_date);
                $overdueDays = $interval->days;
                $overdueFine += ($overdueDays * self::FINE_PER_DAY * $item['quantity']);
            }
        }
        
        // Giới hạn tiền phạt tối đa
        if ($overdueFine > self::MAX_FINE) {
            $overdueFine = self::MAX_FINE;
        }

        $fineModel = new Fine();
        $transactionModel = new Transaction();

        if ($overdueFine > 0) {
            $existingFine = $fineModel->getByBorrowingId($id);
            if ($existingFine) {
                $fineModel->updateAmount($existingFine['id'], $overdueFine);
                $fineTransaction = $transactionModel->getByFineId($existingFine['id']);
                if ($fineTransaction) {
                    // Cập nhật số tiền trong transaction nếu cần (nếu có updateAmount)
                    // Hiện tại Transaction model không có updateAmount, nên sẽ bỏ qua hoặc xem xét thêm vào.
                }
            } else {
                $fineId = $fineModel->create($id, $overdueFine);
                $transactionModel->create($borrowing['user_id'], $id, $fineId, $overdueFine, 'cash', 'pending');
            }
        } else {
            // Nếu không có phạt, cập nhật trạng thái đã trả ngay lập tức
            $borrowingModel->updateStatus($id, 'returned');
        }

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
    public function detail() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: admin.php?action=borrowings_list');
            exit;
        }
        
        $borrowingModel = new Borrowing();
        $userModel = new User();
        $bookModel = new Book();
        $borrowDetailModel = new BorrowDetail();
        
        $borrowing = $this->getBorrowingById($id);
        if (!$borrowing) {
            header('Location: admin.php?action=borrowings_list');
            exit;
        }
        
        $user = $userModel->getById($borrowing['user_id']);
        $borrowDetails = $borrowDetailModel->getByBorrowingId($id);
        
        require __DIR__ . '/../views/admin/borrowing_detail.php';
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