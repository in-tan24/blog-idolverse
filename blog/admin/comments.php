<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $delete = $pdo->prepare('DELETE FROM comments WHERE id = ?');
    $delete->execute([$id]);
    header('Location: /blog/admin/comments.php?msg=deleted');
    exit;
}

$adminStmt = $pdo->prepare('SELECT full_name, email FROM users WHERE id = ? LIMIT 1');
$adminStmt->execute([(int)currentUser()['id']]);
$adminUser = $adminStmt->fetch();

$replyTarget = null;
if (isset($_GET['reply'])) {
    $replyId = (int)$_GET['reply'];
    $replyStmt = $pdo->prepare("SELECT cm.id, cm.post_id, cm.name, p.title AS post_title
    FROM comments cm
    JOIN posts p ON p.id = cm.post_id
    WHERE cm.id = ? LIMIT 1");
    $replyStmt->execute([$replyId]);
    $replyTarget = $replyStmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $replyToId = (int)($_POST['reply_to_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');

    $targetStmt = $pdo->prepare('SELECT id, post_id, name FROM comments WHERE id = ? LIMIT 1');
    $targetStmt->execute([$replyToId]);
    $target = $targetStmt->fetch();

    if ($target && $message !== '') {
        $replyName = (string)($adminUser['full_name'] ?? currentUser()['full_name']) . ' (Admin)';
        $replyEmail = (string)($adminUser['email'] ?? 'admin@blog.local');
        $replyMessage = '@' . $target['name'] . ' ' . $message;

        $insert = $pdo->prepare('INSERT INTO comments (post_id, name, email, message, is_approved) VALUES (?, ?, ?, ?, 1)');
        $insert->execute([(int)$target['post_id'], $replyName, $replyEmail, $replyMessage]);
        header('Location: /blog/admin/comments.php?msg=replied');
        exit;
    }

    header('Location: /blog/admin/comments.php?msg=invalid');
    exit;
}

$comments = $pdo->query("SELECT cm.*, p.title AS post_title
FROM comments cm
JOIN posts p ON p.id = cm.post_id
ORDER BY cm.created_at DESC")->fetchAll();

$pageTitle = 'Moderasi Komentar';
include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1>Moderasi Komentar</h1>
    <?php if (($_GET['msg'] ?? '') === 'replied'): ?><div class="alert alert-success">Balasan admin berhasil dikirim.</div><?php endif; ?>
    <?php if (($_GET['msg'] ?? '') === 'deleted'): ?><div class="alert alert-success">Komentar berhasil dihapus.</div><?php endif; ?>
    <?php if (($_GET['msg'] ?? '') === 'invalid'): ?><div class="alert alert-error">Balasan tidak valid atau komentar target tidak ditemukan.</div><?php endif; ?>

    <?php if ($replyTarget): ?>
        <div class="form-card" style="margin-bottom:16px;">
            <h2>Balas Sebagai Admin</h2>
            <p class="muted">Membalas komentar dari <strong><?= e($replyTarget['name']); ?></strong> pada artikel <strong><?= e($replyTarget['post_title']); ?></strong>.</p>
            <form method="post">
                <input type="hidden" name="reply_to_id" value="<?= (int)$replyTarget['id']; ?>">
                <label>Isi Balasan</label>
                <textarea name="message" required placeholder="Tulis balasan admin..."></textarea>
                <button class="btn" type="submit">Kirim Balasan</button>
                <a class="btn btn-ghost" href="/blog/admin/comments.php">Batal</a>
            </form>
        </div>
    <?php endif; ?>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Artikel</th><th>Nama</th><th>Pesan</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= e($comment['post_title']); ?></td>
                    <td><?= e($comment['name']); ?><br><span class="muted"><?= e($comment['email']); ?></span></td>
                    <td><?= e(strlen($comment['message']) > 90 ? substr($comment['message'], 0, 90) . '...' : $comment['message']); ?></td>
                    <td><?= date('d M Y', strtotime($comment['created_at'])); ?></td>
                    <td>
                        <a class="btn btn-sm" href="/blog/admin/comments.php?reply=<?= (int)$comment['id']; ?>">Balas</a>
                        <a class="btn btn-sm btn-danger" href="/blog/admin/comments.php?delete=<?= (int)$comment['id']; ?>" onclick="return confirm('Hapus komentar ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
