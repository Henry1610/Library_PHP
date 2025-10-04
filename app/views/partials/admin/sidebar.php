<!-- Sidebar -->
<nav class="sidebar-gradient text-white p-4" style="width: 280px; min-height: 100vh;">


    <div class="nav flex-column gap-2">
        <!-- Dashboard -->
        <a href="admin.php?action=dashboard"
            class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'dashboard' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3 10.5V8.8c0-.42.21-.82.56-1.06l4.2-2.94a1.25 1.25 0 0 1 1.48 0l4.2 2.94c.35.24.56.64.56 1.06v1.7c0 .69-.56 1.25-1.25 1.25h-1.25v2.5a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1-.75-.75v-2.5H4.25A1.25 1.25 0 0 1 3 10.5Z"
                        stroke="currentColor" stroke-width="1.5" />
                </svg>
            </div>
            <span class="fw-semibold">Dashboard</span>
        </a>

        <!-- Quản lý Sách -->
        <a href="admin.php?action=books" class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'books' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5 4.5h10a1 1 0 0 1 1 1v9a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9a1 1 0 0 1 1-1Z"
                        stroke="currentColor" stroke-width="1.5" />
                    <path d="M7 7.5h6M7 10.5h6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="fw-semibold">Quản lý Sách</span>
        </a>

        <!-- Quản lý Danh mục -->
        <a href="admin.php?action=categories" class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'categories' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="4" y="4" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.5" />
                    <rect x="11" y="4" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.5" />
                    <rect x="4" y="11" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.5" />
                    <rect x="11" y="11" width="5" height="5" rx="1" stroke="currentColor" stroke-width="1.5" />
                </svg>
            </div>
            <span class="fw-semibold">Quản lý Danh mục</span>
        </a>

        <!-- Quản lý Mượn/Trả -->
        <a href="admin.php?action=borrowings_list"
            class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'borrowings' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 6h12M4 10h12M4 14h12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </div>
            <span class="fw-semibold">Quản lý Mượn/Trả</span>
        </a>

        <!-- Quản lý Người dùng -->
        <a href="admin.php?action=users" class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'users' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="10" cy="7" r="3" stroke="currentColor" stroke-width="1.5" />
                    <path d="M4 16c0-2.21 2.69-4 6-4s6 1.79 6 4" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" />
                </svg>
            </div>
            <span class="fw-semibold">Quản lý Người dùng</span>
        </a>

        <!-- Quản lý Đánh giá -->
        <a href="admin.php?action=reviews" class="nav-link text-white d-flex align-items-center gap-3 rounded-3 p-3 <?= ($activeSidebar ?? '') === 'reviews' ? 'active bg-white bg-opacity-25' : '' ?>">
            <div class="bg-white bg-opacity-25 rounded-2 p-2">
                <svg width="20" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10 2l2.5 5.5L18 8.5l-4 4 1 6-5-3-5 3 1-6-4-4 5.5-1L10 2z" stroke="currentColor"
                        stroke-width="1.5" stroke-linejoin="round" />
                </svg>
            </div>
            <span class="fw-semibold">Quản lý Đánh giá</span>
        </a>
    </div>
</nav>


<style>
    .sidebar-gradient {
        background: linear-gradient(180deg, #6366f1 0%, #8b5cf6 100%);
    }

    .nav-link:hover {
        transform: translateX(3px);
    }

    .nav-link {
        transition: all 0.2s;
    }

    .nav-link.active {
        background-color: rgba(255, 255, 255, 0.25) !important;
        transform: translateX(3px);
    }

    .nav-link.active:hover {
        background-color: rgba(255, 255, 255, 0.3) !important;
    }
</style>