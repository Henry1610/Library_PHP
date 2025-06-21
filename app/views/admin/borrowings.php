<?php include __DIR__ . '/../partials/admin/header.php'; ?>
<h2>Quản lý Yêu cầu mượn/trả sách</h2>
<a href="index.php?action=admin">Về Dashboard</a>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>ID</th><th>User</th><th>Trạng thái mượn</th><th>Duyệt mượn</th><th>Duyệt trả</th><th>Ngày tạo</th><th>Hành động</th>
    </tr>
    <?php foreach ($borrowings as $b): ?>
    <tr>
        <td><?= $b['id'] ?></td>
        <td>
            <?php $u = $userModel->getById($b['user_id']); echo htmlspecialchars($u['name'] ?? ''); ?>
        </td>
        <td><?= htmlspecialchars($b['status']) ?></td>
        <td><?= htmlspecialchars($b['approval_status']) ?></td>
        <td><?= htmlspecialchars($b['return_approval_status']) ?></td>
        <td><?= $b['created_at'] ?></td>
        <td>
            <?php if ($b['approval_status'] === 'pending'): ?>
                <a href="index.php?action=approve_borrowing&id=<?= $b['id'] ?>">Duyệt mượn</a>
            <?php endif; ?>
            <?php if ($b['status'] === 'borrowed' && $b['return_approval_status'] === 'pending'): ?>
                <a href="index.php?action=approve_return&id=<?= $b['id'] ?>">Duyệt trả</a>
            <?php endif; ?>
            <a href="index.php?action=borrowing_detail&id=<?= $b['id'] ?>">Chi tiết</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php include __DIR__ . '/../partials/admin/footer.php'; ?> 