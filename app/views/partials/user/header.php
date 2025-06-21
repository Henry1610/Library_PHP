<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .top-bar { margin-bottom: 20px; }
        .top-bar a { margin-right: 10px; padding: 8px 16px; background: #007bff; color: #fff; text-decoration: none; border-radius: 4px; }
        .top-bar a.register { background: #28a745; }
        .top-bar a.logout { background: #dc3545; }
        .cart-icon {
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
            font-size: 22px;
            color: #007bff;
            background: #fff;
            border-radius: 50%;
            border: 1px solid #007bff;
            width: 36px;
            height: 36px;
            text-align: center;
            line-height: 36px;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="top-bar">
    <?php if (!empty($_SESSION['user'])): ?>
        <a href="index.php?action=borrow_cart" class="cart-icon" title="Giỏ mượn sách">&#128722;</a>
        <a href="index.php?action=logout" class="logout">Đăng xuất</a>
    <?php else: ?>
        <a href="index.php?action=login">Đăng nhập</a>
        <a href="index.php?action=register" class="register">Đăng ký</a>
    <?php endif; ?>
</div>

