<?php include __DIR__ . '/../partials/user/header.php'; ?>
<div class="container py-4">
    <h2 class="mb-4 text-center">Lịch sử mượn/trả của bạn</h2>
    <?php
    require_once __DIR__ . '/../../models/Borrowing.php';
    require_once __DIR__ . '/../../models/BorrowDetail.php';
    require_once __DIR__ . '/../../models/Book.php';
    require_once __DIR__ . '/../../models/Transaction.php';
    require_once __DIR__ . '/../../models/Fine.php';
    require_once __DIR__ . '/../../models/BookReview.php';
    $borrowingModel = new Borrowing();
    $borrowDetailModel = new BorrowDetail();
    $bookModel = new Book();
    $transactionModel = new Transaction();
    $fineModel = new Fine();
    $bookReviewModel = new BookReview();
    $borrowings = $borrowingModel->getByUserId($_SESSION['user']['id']);
    if ($borrowings) {
        echo '<div class="row g-4">';
        foreach ($borrowings as $b) {
            $details = $borrowDetailModel->getByBorrowingId($b['id']);
            // Tính tổng tiền
            $total = 0;
            $date_borrow = $date_return = '';
            foreach ($details as $d) {
                $book = $bookModel->getById($d['book_id']);
                $borrow_date = new DateTime($d['borrow_date']);
                $return_date = new DateTime($d['return_date']);
                $days = $borrow_date <= $return_date ? $borrow_date->diff($return_date)->days + 1 : 1;
                $item_total = $book['price'] * $d['quantity'] * $days;
                $total += $item_total;
                if (!$date_borrow || $borrow_date < new DateTime($date_borrow)) $date_borrow = $d['borrow_date'];
                if (!$date_return || $return_date > new DateTime($date_return)) $date_return = $d['return_date'];
            }
            // Chọn icon trạng thái
            $statusIcon = $b['status']==='returned' ? 'bi-check-circle-fill text-success' : ($b['status']==='borrowed' ? 'bi-journal-arrow-up text-primary' : 'bi-hourglass-split text-secondary');
            $statusText = $b['status']==='returned' ? 'Đã trả' : ($b['status']==='borrowed' ? 'Đang mượn' : 'Chờ duyệt');
            // Card UI
            echo '<div class="col-12">';
            echo '<div class="borrowing-card card border-0 shadow-lg mb-3">';
            // Header
            echo '<div class="borrowing-card-header p-3 rounded-top d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg,#4f8cff,#6dd5ed);">';
            echo '<div class="d-flex align-items-center gap-3">';
            echo '<i class="bi '.$statusIcon.'" style="font-size:2.2rem;"></i>';
            echo '<div>';
            echo '<div class="fw-bold text-white fs-5">'.$statusText.'</div>';
            echo '<div class="small text-white-50">Mã mượn: #'.$b['id'].'</div>';
            echo '</div>';
            echo '</div>';
            echo '<div class="text-end">';
            echo '<span class="badge bg-light text-primary fs-6 px-3 py-2 shadow-sm">Tổng: <b>'.number_format($total,0).' đ</b></span>';
            echo '</div>';
            echo '</div>';
            // Body
            echo '<div class="card-body">';
            echo '<div class="row mb-3">';
            echo '<div class="col-6 col-md-3"><div class="text-muted small">Ngày mượn</div><div class="fw-bold">'.($date_borrow?$date_borrow:'-').'</div></div>';
            echo '<div class="col-6 col-md-3"><div class="text-muted small">Ngày trả dự kiến</div><div class="fw-bold">'.($date_return?$date_return:'-').'</div></div>';
            echo '<div class="col-12 col-md-6"><div class="text-muted small">Trạng thái duyệt</div>';
            echo '<span class="badge bg-info bg-opacity-75 me-2">Duyệt: '.htmlspecialchars($b['approval_status']).'</span>';
            echo '<span class="badge bg-warning bg-opacity-75">Trả: '.htmlspecialchars($b['return_approval_status']).'</span>';
            echo '</div>';
            echo '</div>';
            // Sách đã mượn dạng grid nhỏ
            echo '<div class="mb-3"><div class="text-muted small mb-1">Sách đã mượn:</div>';
            echo '<div class="row g-2">';
            foreach ($details as $d) {
                $book = $bookModel->getById($d['book_id']);
                echo '<div class="col-12 col-sm-6 col-md-4 col-lg-3">';
                echo '<div class="borrowing-book-item d-flex align-items-center p-2 rounded bg-light h-100">';
                if (!empty($book['cover_img'])) {
                    echo '<img src="'.htmlspecialchars($book['cover_img']).'" alt="cover" class="rounded me-2" style="width:38px;height:52px;object-fit:cover;">';
                }
                echo '<div class="flex-grow-1">';
                echo '<div class="fw-bold">'.htmlspecialchars($book['title']).'</div>';
                echo '<div class="small text-muted">x'.$d['quantity'].' | '.$d['borrow_date'].' → '.$d['return_date'].'</div>';
                
                // Thêm nút đánh giá cho sách đã mượn
                if ($b['status'] === 'borrowed' || $b['status'] === 'returned') {
                    $hasReviewed = $bookReviewModel->hasReviewed($_SESSION['user']['id'], $d['book_id'], $b['id']);
                    if (!$hasReviewed) {
                        echo '<div class="mt-1">';
                        echo '<a href="index.php?action=show_review_form&book_id='.$d['book_id'].'&borrowing_id='.$b['id'].'" class="btn btn-sm btn-outline-warning">';
                        echo '<i class="bi bi-star"></i> Đánh giá';
                        echo '</a>';
                        echo '</div>';
                    } else {    
                        echo '<div class="mt-1">';
                        echo '<span class="badge bg-success">Đã đánh giá</span>';
                        echo '</div>';
                    }
                }
                
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div></div>';
            // Hành động
            echo '<div class="d-flex flex-wrap gap-2 mt-2">';
            $transaction = $transactionModel->getByBorrowingId($b['id']);
            if ($b['approval_status'] === 'approved' && $b['status'] !== 'borrowed') {
                if ($transaction && $transaction['status'] !== 'success') {
                    echo '<a href="index.php?action=borrowing_payment&id='.$b['id'].'" class="btn btn-outline-primary btn-sm fw-bold"><i class="bi bi-credit-card me-1"></i>Thanh toán VNPay</a>';
                    echo '<small class="d-block text-muted mt-1">Thời gian hết hạn: 60 phút</small>';
                } else {
                    echo '<span class="btn btn-light btn-sm disabled">Chờ thanh toán</span>';
                }
            } else if ($b['status'] === 'borrowed' || $b['status'] === 'returned') {
                echo '<span class="btn btn-success btn-sm disabled"><i class="bi bi-check-circle me-1"></i>Đã thanh toán</span>';
            }
            $fine = $fineModel->getByBorrowingId($b['id']);
            if ($b['return_approval_status'] === 'approved' && $b['status'] !== 'returned' && $fine) {
                $fineTrans = $transactionModel->getByFineId($fine['id']);
                if ($fineTrans && $fineTrans['status'] !== 'success') {
                    echo '<a href="index.php?action=return_payment&id='.$b['id'].'" class="btn btn-outline-danger btn-sm fw-bold">Thanh toán phạt</a>';
                } else {
                    echo '<span class="btn btn-light btn-sm disabled">Chờ thanh toán</span>';
                }
            } else if ($b['status'] === 'returned') {
                echo '<span class="btn btn-success btn-sm disabled">Đã thanh toán</span>';
            }
            if ($b['status'] === 'borrowed' && $b['return_approval_status'] === 'pending') {
                echo '<span class="btn btn-warning btn-sm disabled">Đã yêu cầu trả</span>';
            } else if ($b['status'] === 'borrowed' && $b['return_approval_status'] !== 'pending') {
                echo '<a href="index.php?action=request_return&id='.$b['id'].'" class="btn btn-outline-secondary btn-sm fw-bold">Yêu cầu trả sách</a>';
            }
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="alert alert-info text-center p-5 fs-4"><i class="bi bi-emoji-smile text-primary fs-1 mb-3"></i><br>Bạn chưa có lịch sử mượn/trả nào.</div>';
    }
    ?>
</div>
<style>
.borrowing-card {transition: box-shadow 0.2s, transform 0.2s;}
.borrowing-card:hover {box-shadow: 0 6px 24px rgba(0,0,0,0.12); transform: translateY(-2px) scale(1.01);}
.borrowing-card-header {background: linear-gradient(90deg,#4f8cff,#6dd5ed)!important;}
.borrowing-book-item {transition: box-shadow 0.2s;}
.borrowing-book-item:hover {box-shadow: 0 2px 8px rgba(0,0,0,0.08); background: #eaf6ff;}
.card .badge {font-size: 1rem;}
.card .btn {font-size: 1rem;}
.card ul {margin-bottom: 0;}
</style>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 