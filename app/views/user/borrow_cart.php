<?php 
$pageTitle = 'Giỏ Mượn Sách - E-Library';
include __DIR__ . '/../partials/user/header.php'; 
?>
<div class="container py-4">
    <h2 class="mb-4 text-center">Giỏ mượn sách của bạn</h2>
    <?php
    require_once __DIR__ . '/../../models/Book.php';
    require_once __DIR__ . '/../../models/Category.php';
    if (empty($_SESSION['user'])) {
        echo '<div class="alert alert-warning text-center">Vui lòng <a href="index.php?action=login">đăng nhập</a> để xem giỏ mượn.</div>';
    } else {
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            echo '<div class="alert alert-info text-center">Giỏ mượn của bạn đang trống.</div>';
        } else {
            $bookModel = new Book();
            $categoryModel = new Category();
            $categories = $categoryModel->getAll();
            $catMap = [];
            foreach ($categories as $cat) {
                $catMap[$cat['id']] = $cat['name'];
            }
            $total = 0;
            echo '<div class="borrow-list-modern">';
            foreach ($cart as $key => $item) {
                $book = $bookModel->getById($item['book_id']);
                $borrow_date = new DateTime($item['borrow_date']);
                $return_date = new DateTime($item['return_date']);
                $days = $borrow_date <= $return_date ? $borrow_date->diff($return_date)->days + 1 : 1;
                $item_total = $book['price'] * $item['quantity'] * $days;
                $total += $item_total;
                echo '<div class="borrow-item-card d-flex align-items-center py-3 px-2 border-bottom gap-3 flex-wrap flex-md-nowrap">';
                // Ảnh bìa
                echo '<div class="flex-shrink-0">';
                if (!empty($book['cover_img'])) {
                    echo '<img src="' . htmlspecialchars($book['cover_img']) . '" alt="cover" class="rounded shadow-sm" style="width:60px;height:80px;object-fit:cover;">';
                } else {
                    echo '<div class="bg-light d-flex align-items-center justify-content-center rounded" style="width:60px;height:80px;">'
                        .'<span class="text-muted small">Không có ảnh</span></div>';
                }
                echo '</div>';
                // Thông tin sách
                echo '<div class="flex-grow-1 min-w-0">';
                echo '<div class="fw-bold fs-5 mb-1"><a href="index.php?action=book_detail&id=' . $book['id'] . '" class="text-decoration-none text-dark">' . htmlspecialchars($book['title']) . '</a></div>';
                echo '<div class="text-muted small mb-1">Tác giả: ' . htmlspecialchars($book['author']) . '</div>';
                echo '<div class="d-flex flex-wrap gap-3 mt-1">';
                echo '<span class="badge bg-primary bg-opacity-10 text-primary fw-normal">Số lượng: <b>' . $item['quantity'] . '</b></span>';
                echo '<span class="badge bg-success bg-opacity-10 text-success fw-normal">Ngày mượn: <b>' . $item['borrow_date'] . '</b></span>';
                echo '<span class="badge bg-warning bg-opacity-10 text-warning fw-normal">Ngày trả: <b>' . $item['return_date'] . '</b></span>';
                echo '</div>';
                echo '</div>';
                // Giá và nút xóa
                echo '<div class="text-end flex-shrink-0 ms-auto" style="min-width:120px;">';
                echo '<div class="text-danger fw-bold fs-6">' . number_format($book['price'], 0) . ' đ/ngày</div>';
                echo '<div class="text-success fw-bold fs-5">' . number_format($item_total, 0) . ' đ</div>';
                echo '<a href="index.php?action=remove_from_cart&key=' . $key . '" class="btn btn-link text-danger px-2" title="Xóa"><i class="bi bi-trash fs-4"></i></a>';
                echo '</div>';
                echo '</div>';
            }
            echo '</div>';
            // Bố cục 2 cột: trái là ô thông báo, phải là ô tổng tiền
            echo '<div class="row mt-4 g-3">';
            // Ô thông báo bên trái
            echo '<div class="col-12 col-md-7 col-lg-8">';
            echo '<div class="card shadow-sm border-1 border-info mb-3">';
            echo '<div class="card-body">';
            echo '<div class="fs-5 text-info mb-2"><i class="bi bi-info-circle me-2"></i>Thông báo</div>';
            echo '<div class="small text-secondary mb-2">Tổng tiền mượn được tính bằng: <b>Giá/ngày x Số lượng x Số ngày mượn</b> cho tất cả sách trong giỏ. Số ngày mượn được tính từ ngày mượn đến ngày trả dự kiến. Nếu trả muộn, bạn có thể bị tính thêm phí theo quy định của thư viện.</div>';
            echo '<div class="small text-secondary mb-2">Vui lòng kiểm tra kỹ thông tin sách, số lượng và ngày mượn/trả trước khi xác nhận. Sau khi xác nhận, yêu cầu mượn sẽ được gửi đến quản trị viên để duyệt.</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            // Ô tổng tiền bên phải
            echo '<div class="col-12 col-md-5 col-lg-4">';
            echo '<div class="card shadow-sm border-2 border-primary mb-3">';
            echo '<div class="card-body text-end">';
            echo '<div class="fs-5 text-muted mb-2"><i class="bi bi-cash-coin me-2"></i>Tổng tiền mượn</div>';
            echo '<div class="display-6 fw-bold text-danger mb-3">' . number_format($total, 0) . ' đ</div>';
            echo '<a href="index.php?action=checkout" class="btn btn-success btn-lg w-100"><i class="bi bi-check-circle me-2"></i>Xác nhận mượn sách</a>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
</div>
<style>
.borrow-list-modern {background: #fff; border-radius: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.04);}
.borrow-item-card {font-family: "Segoe UI", "Roboto", Arial, sans-serif; font-size: 1.08rem; border-bottom: 1.5px solid #e5e7eb !important;}
.borrow-item-card:last-child {border-bottom: none !important;}
.borrow-item-card a {transition: color 0.2s;}
.borrow-item-card a:hover {color: #0d6efd;}
.badge {font-size: 0.98rem; padding: 0.5em 0.8em; border-radius: 0.7em;}
</style>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 