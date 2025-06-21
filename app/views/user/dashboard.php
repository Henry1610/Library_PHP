<?php include __DIR__ . '/../partials/user/header.php'; ?>
<h2>Danh sách Sách</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Ảnh bìa</th><th>Tiêu đề</th><th>Tác giả</th><th>Nhà XB</th><th>Năm</th><th>Danh mục</th><th>ISBN</th><th>Số lượng</th><th>Còn lại</th><th>Giá</th><th>Hành động</th>
    </tr>
    <?php 
    require_once __DIR__ . '/../../models/Book.php';
    $bookModel = new Book();
    $books = $bookModel->getAll();
    foreach ($books as $book): ?>
    <tr>
        <td>
            <?php if (!empty($book['cover_img'])): ?>
                <img src="<?= htmlspecialchars($book['cover_img']) ?>" alt="cover" style="max-width:60px;max-height:80px;">
            <?php else: ?>
                <span>Không có ảnh</span>
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($book['title']) ?></td>
        <td><?= htmlspecialchars($book['author']) ?></td>
        <td><?= htmlspecialchars($book['publisher']) ?></td>
        <td><?= htmlspecialchars($book['year']) ?></td>
        <td><?= htmlspecialchars($book['category_id']) ?></td>
        <td><?= htmlspecialchars($book['isbn']) ?></td>
        <td><?= $book['quantity'] ?></td>
        <td><?= $book['available'] ?></td>
        <td><?= number_format($book['price'], 2) ?> đ</td>
        <td>
            <?php if (empty($_SESSION['user'])): ?>
                <a href="index.php?action=login">Mượn</a>
            <?php else: ?>
                <button type="button" class="btn btn-primary btn-borrow" 
                    data-bs-toggle="modal" data-bs-target="#borrowModal"
                    data-book-id="<?= $book['id'] ?>"
                    data-title="<?= htmlspecialchars($book['title']) ?>"
                    data-available="<?= $book['available'] ?>">
                    Mượn
                </button>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>

<?php
if (!empty($_SESSION['user'])) {
    require_once __DIR__ . '/../../models/Borrowing.php';
    require_once __DIR__ . '/../../models/Transaction.php';
    require_once __DIR__ . '/../../models/Fine.php';
    $borrowingModel = new Borrowing();
    $transactionModel = new Transaction();
    $fineModel = new Fine();
    $borrowings = $borrowingModel->getByUserId($_SESSION['user']['id']);
    if ($borrowings) {
        echo '<h3>Lịch sử mượn/trả của bạn</h3>';
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<tr><th>ID</th><th>Trạng thái mượn</th><th>Duyệt mượn</th><th>Duyệt trả</th><th>Thanh toán mượn</th><th>Thanh toán phạt</th><th>Hành động</th></tr>';
        foreach ($borrowings as $b) {
            echo '<tr>';
            echo '<td>' . $b['id'] . '</td>';
            echo '<td>' . htmlspecialchars($b['status']) . '</td>';
            echo '<td>' . htmlspecialchars($b['approval_status']) . '</td>';
            echo '<td>' . htmlspecialchars($b['return_approval_status']) . '</td>';
            // Thanh toán mượn
            $transaction = $transactionModel->getByBorrowingId($b['id']);
            if ($b['approval_status'] === 'approved' && $b['status'] !== 'borrowed') {
                if ($transaction && $transaction['status'] !== 'success') {
                    echo '<td><a href="index.php?action=borrowing_payment&id=' . $b['id'] . '">Thanh toán</a></td>';
                } else {
                    echo '<td>Chờ thanh toán</td>';
                }
            } else if ($b['status'] === 'borrowed' || $b['status'] === 'returned') {
                echo '<td>Đã thanh toán</td>';
            } else {
                echo '<td>-</td>';
            }
            // Thanh toán phạt
            $fine = $fineModel->getByBorrowingId($b['id']);
            if ($b['return_approval_status'] === 'approved' && $b['status'] !== 'returned' && $fine) {
                $fineTrans = $transactionModel->getByFineId($fine['id']);
                if ($fineTrans && $fineTrans['status'] !== 'success') {
                    echo '<td><a href="index.php?action=return_payment&id=' . $b['id'] . '">Thanh toán phạt</a></td>';
                } else {
                    echo '<td>Chờ thanh toán</td>';
                }
            } else if ($b['status'] === 'returned') {
                echo '<td>Đã thanh toán</td>';
            } else {
                echo '<td>-</td>';
            }
            // Hành động trả sách
            if ($b['status'] === 'borrowed' && $b['return_approval_status'] === 'pending') {
                echo '<td>Đã yêu cầu trả</td>';
            } else if ($b['status'] === 'borrowed' && $b['return_approval_status'] !== 'pending') {
                echo '<td><a href="index.php?action=request_return&id=' . $b['id'] . '">Yêu cầu trả sách</a></td>';
            } else {
                echo '<td>-</td>';
            }
            echo '</tr>';
        }
        echo '</table>';
    }
}
?>

<!-- Modal Bootstrap -->
<div class="modal fade" id="borrowModal" tabindex="-1" aria-labelledby="borrowModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="post" action="index.php?action=add_to_cart">
        <div class="modal-header">
          <h5 class="modal-title" id="borrowModalLabel">Mượn sách</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="book_id" id="modal-book-id">
          <div class="mb-3">
            <label for="modal-title" class="form-label">Tên sách</label>
            <input type="text" class="form-control" id="modal-title" readonly>
          </div>
          <div class="mb-3">
            <label for="modal-borrow-date" class="form-label">Ngày mượn</label>
            <input type="date" class="form-control" name="borrow_date" id="modal-borrow-date" value="<?= date('Y-m-d') ?>" required>
          </div>
          <div class="mb-3">
            <label for="modal-return-date" class="form-label">Ngày trả dự kiến</label>
            <input type="date" class="form-control" name="return_date" id="modal-return-date" required>
          </div>
          <div class="mb-3">
            <label for="modal-quantity" class="form-label">Số lượng</label>
            <input type="number" class="form-control" name="quantity" id="modal-quantity" min="1" value="1" required>
            <div id="max-available" class="form-text"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success">Thêm vào giỏ mượn</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bootstrap JS & custom JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var borrowModal = document.getElementById('borrowModal');
    var bookIdInput = document.getElementById('modal-book-id');
    var titleInput = document.getElementById('modal-title');
    var quantityInput = document.getElementById('modal-quantity');
    var maxAvailable = document.getElementById('max-available');
    var borrowButtons = document.querySelectorAll('.btn-borrow');
    borrowButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            var bookId = this.getAttribute('data-book-id');
            var title = this.getAttribute('data-title');
            var available = this.getAttribute('data-available');
            bookIdInput.value = bookId;
            titleInput.value = title;
            quantityInput.max = available;
            maxAvailable.textContent = 'Tối đa: ' + available + ' cuốn';
        });
    });
});
</script>

<?php include __DIR__ . '/../partials/user/footer.php'; ?> 