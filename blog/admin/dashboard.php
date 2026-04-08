<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

$pageTitle = 'Admin Dashboard';
$totalPosts = (int)$pdo->query('SELECT COUNT(*) FROM posts')->fetchColumn();
$totalCategories = (int)$pdo->query('SELECT COUNT(*) FROM categories')->fetchColumn();
$totalTags = (int)$pdo->query('SELECT COUNT(*) FROM tags')->fetchColumn();
$totalComments = (int)$pdo->query('SELECT COUNT(*) FROM comments')->fetchColumn();
$totalAuthors = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'author'")->fetchColumn();
$totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE role = 'user'")->fetchColumn();

include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1>Dashboard Admin</h1>
    <p class="muted">Selamat datang, <?= e(currentUser()['full_name']); ?>.</p>

    <div class="stats">
        <div class="stat"><h3><?= $totalPosts; ?></h3><p>Total Artikel</p></div>
        <div class="stat"><h3><?= $totalCategories; ?></h3><p>Kategori</p></div>
        <div class="stat"><h3><?= $totalTags; ?></h3><p>Tag</p></div>
        <div class="stat"><h3><?= $totalComments; ?></h3><p>Komentar</p></div>
        <div class="stat"><h3><?= $totalAuthors; ?></h3><p>Total Author</p></div>
        <div class="stat"><h3><?= $totalUsers; ?></h3><p>Total User</p></div>
    </div>

    <div class="card-grid">
        <a class="form-card" href="/blog/admin/posts.php"><h3>Kelola Artikel</h3></a>
        <a class="form-card" href="/blog/admin/categories.php"><h3>Kelola Kategori</h3></a>
        <a class="form-card" href="/blog/admin/tags.php"><h3>Kelola Tag</h3></a>
        <a class="form-card" href="/blog/admin/comments.php"><h3>Kelola Komentar</h3></a>
        <a class="form-card" href="/blog/admin/users.php"><h3>Kelola Akun</h3></a>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>