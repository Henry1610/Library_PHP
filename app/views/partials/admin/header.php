<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin Dashboard' ?></title>
    
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
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            color: #222d32;
            padding: 0 32px;
            height: 64px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.07);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .top-bar .logo {
            display: flex;
            align-items: center;
            font-weight: bold;
            font-size: 1.5rem;
            letter-spacing: 1px;
            color: #007bff;
        }
        .top-bar .logo svg {
            margin-right: 10px;
        }
        .top-bar .admin-info {
            font-size: 1rem;
            margin-right: 18px;
            display: flex;
            align-items: center;
        }
        .top-bar .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #007bff;
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 1.1rem;
            margin-right: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .top-bar .logout-btn {
            background: linear-gradient(90deg,#ff5858,#f09819);
            color: #fff;
            border: none;
            padding: 9px 22px;
            border-radius: 22px;
            text-decoration: none;
            font-weight: 500;
            margin-left: 10px;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(255,88,88,0.08);
            transition: background 0.2s, box-shadow 0.2s;
        }
        .top-bar .logout-btn:hover {
            background: linear-gradient(90deg,#f09819,#ff5858);
            box-shadow: 0 4px 16px rgba(255,88,88,0.16);
        }
        @media (max-width: 600px) {
            .top-bar { flex-direction: column; height: auto; padding: 10px; }
            .top-bar .logo { font-size: 1.1rem; }
            .top-bar .admin-info { font-size: 0.95rem; margin-right: 0; }
        }
        /* Sidebar & Layout CSS từ dashboard */
        .admin-layout {
            display: flex;
            min-height: 90vh;
        }
        .sidebar {
            width: 240px;
            background: linear-gradient(180deg,#007bff 0%,#0056b3 100%);
            color: #fff;
            padding-top: 32px;
            display: flex;
            flex-direction: column;
            box-shadow: 2px 0 16px rgba(0,0,0,0.08);
            border-top-right-radius: 18px;
            border-bottom-right-radius: 18px;
            margin: 24px 0 24px 18px;
            min-height: 80vh;
        }
        .sidebar a {
            color: #fff;
            text-decoration: none;
            padding: 16px 36px;
            font-size: 1.13rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-left: 4px solid transparent;
            border-radius: 0 24px 24px 0;
            margin-bottom: 6px;
            transition: background 0.18s, border 0.18s, color 0.18s;
        }
        .sidebar a.active, .sidebar a:hover {
            background: rgba(255,255,255,0.13);
            border-left: 4px solid #fff;
            color: #ffe082;
        }
        .main-content {
            flex: 1;
            padding: 48px 56px;
            background: #f4f6f9;
            margin: 24px 24px 24px 0;
            border-radius: 18px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.07);
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
            .admin-layout { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; border-radius: 0; margin: 0; min-height: unset; }
            .sidebar a { flex: 1; justify-content: center; border-radius: 0; padding: 14px 0; font-size: 1rem; }
            .main-content { margin: 0; border-radius: 0; padding: 24px 8px; }
        }
    </style>
</head>
<body>
<div class="top-bar">
    <div class="logo">
        <svg width="32" height="32" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="32" height="32" rx="8" fill="#007bff"/><path d="M10 24V10a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/><path d="M10 24h12" stroke="#fff" stroke-width="2" stroke-linecap="round"/></svg>
        Quản trị Thư viện
    </div>
    <div style="display:flex;align-items:center;">
        <span class="admin-info">
            <span class="avatar"><?php echo strtoupper(substr($_SESSION['user']['name'],0,1)); ?></span>
            <?php echo htmlspecialchars($_SESSION['user']['name']); ?> (Admin)
        </span>
        <a href="index.php?action=logout" class="logout-btn">Đăng xuất</a>
    </div>
</div> 