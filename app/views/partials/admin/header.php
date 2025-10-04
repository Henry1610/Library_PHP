<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'E-Library - Admin Dashboard' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        @media (max-width: 600px) {
            .top-bar {
                flex-direction: column;
                height: auto;
                padding: 10px;
            }

            .top-bar .logo {
                font-size: 1.1rem;
            }

            .top-bar .admin-info {
                font-size: 0.95rem;
                margin-right: 0;
            }
        }

        /* Sidebar & Layout CSS từ dashboard */
        .admin-layout {
            display: flex;
            min-height: 90vh;
        }

        .main-content {
            flex: 1;
            padding: 48px 56px;
            background: #f4f6f9;
            margin: 24px 24px 24px 0;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.07);
            min-width: 0;
        }

        .main-content h2 {
            margin-top: 0;
            font-size: 2.2rem;
            color: #007bff;
            font-weight: 700;
        }

        .main-content p {
            color: #444;
            font-size: 1.15rem;
        }

        .main-content ul {
            margin-top: 18px;
            padding-left: 22px;
            color: #333;
            font-size: 1.08rem;
        }

        @media (max-width: 900px) {
            .admin-layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                flex-direction: row;
                border-radius: 0;
                margin: 0;
                min-height: unset;
            }

            .sidebar a {
                flex: 1;
                justify-content: center;
                border-radius: 0;
                padding: 14px 0;
                font-size: 1rem;
            }

            .main-content {
                margin: 0;
                border-radius: 0;
                padding: 24px 8px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top" style="background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 100%);">
        <div class="container-fluid px-4">
            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center fw-bold text-primary fs-4 text-white" href="#">
                <i class="bi bi-book-half me-2 fs-3"></i>
                <span class="d-none d-md-inline ">Quản trị Thư viện</span>
                <span class="d-inline d-md-none">Admin</span>
            </a>

            <!-- User Info & Logout -->
            <div class="d-flex align-items-center gap-3">
                <!-- User Avatar & Name -->
                <div class="d-none d-sm-flex align-items-center gap-2">

                    <div class="d-none d-lg-block">
                        <div class="text-small fw-bold text-white">
                            <?php echo htmlspecialchars($_SESSION['user']['name']); ?>
                        </div>
                        <div class="text-white " style="font-size: 0.75rem;">Quản trị viên</div>
                    </div>
                </div>

                <!-- Logout Button -->
                <a href="index.php?action=logout"
                    class="btn btn-danger logout-gradient text-white border-0 rounded-pill px-3 py-2 fw-semibold d-flex align-items-center gap-2"
                    style="transition: all 0.3s;">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-sm-inline">Đăng xuất</span>
                </a>
            </div>
        </div>
    </nav>