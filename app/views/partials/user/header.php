<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title><?= $pageTitle ?? 'E-Library - Hệ thống cho mượn sách uy tín và tiện lợi' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* Gradient background with fallback */
        nav {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        /* Logo styling */
        .logo {
            font-size: 1.75rem;
            font-weight: 700;
            color: #0d6efd !important;
            transition: transform 0.3s ease;
        }

        .logo:hover {
            transform: scale(1.05);
        }

        /* Center navigation */
        .nav-center .nav-link {
            margin: 0 1.25rem;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-center .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -4px;
            left: 0;
            background-color: #0d6efd;
            transition: width 0.3s ease;
        }

        .nav-center .nav-link:hover::after {
            width: 100%;
        }

        .nav-center .nav-link:hover {
            color: #0d6efd;
        }

        /* Auth navigation */
        .nav-auth .nav-link {
            margin-left: 1rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-auth .nav-link:hover {
            color: #0d6efd;
            transform: translateY(-2px);
        }

        .nav-auth .nav-link i {
            font-size: 1.2rem;
            transition: transform 0.3s ease;
        }

        .nav-auth .nav-link:hover i {
            transform: scale(1.1);
        }

        /* Cart, wishlist and history icons */
        .nav-cart,
        .nav-wishlist,
        .nav-history {
            color: #333;
        }

        .nav-cart:hover,
        .nav-wishlist:hover,
        .nav-history:hover {
            color: #0d6efd;
        }

        /* Wishlist specific styling */
        .nav-wishlist i {
            color: #dc3545;
        }

        .nav-wishlist:hover i {
            color: #b02a37;
        }

        /* Logout icon */
        .nav-logout i {
            color: #dc3545;
        }

        .nav-logout:hover i {
            color: #b02a37;
        }

        /* Register link */
        .text-success {
            color: #198754 !important;
        }

        .text-success:hover {
            color: #146c43 !important;
        }

        /* Mobile menu toggle */
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba(13,110,253,0.75)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Responsive adjustments */
        @media (max-width: 991px) {
            .nav-center {
                margin-top: 1rem;
                flex-direction: column;
                align-items: center;
            }

            .nav-center .nav-link {
                margin: 0.5rem 0;
                font-size: 1.2rem;
            }

            .nav-auth {
                flex-direction: column;
                align-items: center;
                margin-top: 1rem;
            }

            .nav-auth .nav-link {
                margin: 0.5rem 0;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-white shadow-sm">
        <div class="container p-2 ">
            <!-- Logo trái -->
            <a href="index.php" class="logo text-decoration-none">📚 E-Library</a>

            <!-- Toggle button for mobile -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Navbar content -->
            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Menu giữa -->
                <div class="nav-center mx-auto d-lg-flex align-items-center">
                    <a class="nav-link" href="index.php">Trang Chủ</a>
                    <a class="nav-link" href="index.php?action=books">Sách</a>
                    <a class="nav-link" href="index.php?action=contact">Liên Hệ</a>
                    <a class="nav-link" href="index.php?action=help">Trợ Giúp</a>
                </div>

                <!-- Auth phải -->
                <div class="nav-auth d-flex align-items-center">
                    <?php if (!empty($_SESSION['user'])): ?>
                        <a class="nav-link nav-cart" href="index.php?action=borrow_cart" title="Giỏ mượn sách">
                            <i class="fa-solid fa-cart-shopping"></i>
                        </a>
                        <a class="nav-link nav-wishlist" href="index.php?action=wishlist" title="Danh sách yêu thích">
                            <i class="fa-solid fa-heart"></i>
                        </a>
                        <a class="nav-link nav-history" href="index.php?action=borrowing_history" title="Lịch sử mượn sách">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                        <a class="nav-link nav-logout" href="index.php?action=logout" title="Đăng xuất">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    <?php else: ?>
                        <a class="nav-link" href="index.php?action=login">
                            <i class="fa-solid fa-right-to-bracket"></i> Đăng Nhập
                        </a>
                        <a class="nav-link text-success" href="index.php?action=register">
                            <i class="fa-solid fa-registered"></i> Đăng Ký
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>