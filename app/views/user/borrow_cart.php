<?php include __DIR__ . '/../partials/user/header.php'; ?>
<h2>Giỏ mượn sách của bạn</h2>
<?php
require_once __DIR__ . '/../../models/Book.php';
if (empty($_SESSION['user'])) {
    echo '<p>Vui lòng <a href="index.php?action=login">đăng nhập</a> để xem giỏ mượn.</p>';
} else {
    $cart = $_SESSION['cart'] ?? [];
    if (empty($cart)) {
        echo '<p>Giỏ mượn của bạn đang trống.</p>';
    } else {
        echo '<table border="1" cellpadding="5" cellspacing="0">';
        echo '<tr><th>Tên sách</th><th>Số lượng</th><th>Ngày mượn</th><th>Ngày trả dự kiến</th><th>Hành động</th></tr>';
        $bookModel = new Book();
        foreach ($cart as $key => $item) {
            $book = $bookModel->getById($item['book_id']);
            echo '<tr>';
            echo '<td>' . htmlspecialchars($book['title']) . '</td>';
            echo '<td>' . $item['quantity'] . '</td>';
            echo '<td>' . $item['borrow_date'] . '</td>';
            echo '<td>' . $item['return_date'] . '</td>';
            echo '<td><a href="index.php?action=remove_from_cart&key=' . $key . '">Xóa</a></td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '<br><a href="index.php?action=checkout">Xác nhận mượn sách</a>';
    }
}
?>
<?php include __DIR__ . '/../partials/user/footer.php'; ?> 