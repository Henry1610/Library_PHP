<nav class="sidebar">
    <a href="admin.php?action=dashboard" class="<?= (isset($activeSidebar) && $activeSidebar==='dashboard') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M5 12.5V10.8c0-.42.21-.82.56-1.06l4.2-2.94a1.25 1.25 0 0 1 1.48 0l4.2 2.94c.35.24.56.64.56 1.06v1.7c0 .69-.56 1.25-1.25 1.25h-1.25v2.5a.75.75 0 0 1-.75.75h-4.5a.75.75 0 0 1-.75-.75v-2.5H6.25A1.25 1.25 0 0 1 5 12.5Z" stroke="#fff" stroke-width="1.3"/></svg>
        Dashboard
    </a>
    <a href="admin.php?action=books" class="<?= (isset($activeSidebar) && $activeSidebar==='books') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M7 6.5h8a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-7a1 1 0 0 1 1-1Z" stroke="#fff" stroke-width="1.3"/><path d="M9 9.5h4" stroke="#fff" stroke-width="1.3" stroke-linecap="round"/></svg>
        Quản lý Sách
    </a>
    <a href="admin.php?action=categories" class="<?= (isset($activeSidebar) && $activeSidebar==='categories') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M7 7.5h8a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1Z" stroke="#fff" stroke-width="1.3"/><path d="M9 10.5h4" stroke="#fff" stroke-width="1.3" stroke-linecap="round"/></svg>
        Quản lý Danh mục
    </a>
    <a href="admin.php?action=borrowings_list" class="<?= (isset($activeSidebar) && $activeSidebar==='borrowings') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M7 11.5h8M7 14.5h8M7 8.5h8" stroke="#fff" stroke-width="1.3" stroke-linecap="round"/></svg>
        Quản lý Mượn/Trả
    </a>
    <a href="admin.php?action=users" class="<?= (isset($activeSidebar) && $activeSidebar==='users') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M11 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4Z" stroke="#fff" stroke-width="1.3"/></svg>
        Quản lý Người dùng
    </a>
    <a href="admin.php?action=reviews" class="<?= (isset($activeSidebar) && $activeSidebar==='reviews') ? 'active' : '' ?>">
        <svg width="22" height="22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect width="22" height="22" rx="6" fill="#fff" fill-opacity=".12"/><path d="M11 15.8l-3.2-1.8a1 1 0 0 0-1.5.9v3.5a1 1 0 0 0 .5.9l3.2 1.8a1 1 0 0 0 1 0l3.2-1.8a1 1 0 0 0 .5-.9v-3.5a1 1 0 0 0-1.5-.9L11 15.8z" stroke="#fff" stroke-width="1.3"/><path d="M11 12.8l-3.2-1.8a1 1 0 0 0-1.5.9v3.5a1 1 0 0 0 .5.9l3.2 1.8a1 1 0 0 0 1 0l3.2-1.8a1 1 0 0 0 .5-.9v-3.5a1 1 0 0 0-1.5-.9L11 12.8z" stroke="#fff" stroke-width="1.3"/><path d="M11 9.8l-3.2-1.8a1 1 0 0 0-1.5.9v3.5a1 1 0 0 0 .5.9l3.2 1.8a1 1 0 0 0 1 0l3.2-1.8a1 1 0 0 0 .5-.9v-3.5a1 1 0 0 0-1.5-.9L11 9.8z" stroke="#fff" stroke-width="1.3"/></svg>
        Quản lý Đánh giá
    </a>
</nav> 