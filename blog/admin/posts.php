<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
    $stmt->execute([$id]);
    header('Location: /blog/admin/posts.php?msg=deleted');
    exit;
}

$posts = $pdo->query("SELECT p.id, p.title, p.slug, p.created_at, p.is_published, c.name AS category_name, u.full_name AS author_name
FROM posts p
JOIN categories c ON c.id = p.category_id
JOIN users u ON u.id = p.user_id
ORDER BY p.created_at DESC")->fetchAll();

$pageTitle = 'Kelola Artikel';
include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1>Kelola Artikel</h1>
    <?php if (($_GET['msg'] ?? '') === 'deleted'): ?><div class="alert alert-success">Artikel berhasil dihapus.</div><?php endif; ?>
    <p><a class="btn" href="/blog/admin/post_form.php">+ Tambah Artikel</a></p>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Judul</th><th>Kategori</th><th>Author</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($posts as $post): ?>
                <tr>
                    <td><?= e($post['title']); ?></td>
                    <td><?= e($post['category_name']); ?></td>
                    <td><?= e($post['author_name']); ?></td>
                    <td><?= $post['is_published'] ? 'Published' : 'Draft'; ?></td>
                    <td><?= date('d M Y', strtotime($post['created_at'])); ?></td>
                    <td>
                        <a class="btn btn-sm" href="/blog/admin/post_form.php?id=<?= (int)$post['id']; ?>">Edit</a>
                        <a class="btn btn-sm btn-danger" href="/blog/admin/posts.php?delete=<?= (int)$post['id']; ?>" onclick="return confirm('Hapus artikel ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>