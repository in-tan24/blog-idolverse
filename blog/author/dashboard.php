<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['author', 'admin']);

$user = currentUser();
$userId = (int)$user['id'];

$stmt = $pdo->prepare('SELECT COUNT(*) FROM posts WHERE user_id = ?');
$stmt->execute([$userId]);
$totalMyPosts = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) FROM comments cm JOIN posts p ON p.id = cm.post_id WHERE p.user_id = ?');
$stmt->execute([$userId]);
$totalMyComments = (int)$stmt->fetchColumn();

$pageTitle = 'Author Dashboard';
include __DIR__ . '/../includes/header.php';
?>
<div class="container">
    <h1>Dashboard Author</h1>
    <p class="muted">Halo, <?= e($user['full_name']); ?>. Kelola artikelmu di sini.</p>

    <div class="stats">
        <div class="stat"><h3><?= $totalMyPosts; ?></h3><p>Artikel Saya</p></div>
        <div class="stat"><h3><?= $totalMyComments; ?></h3><p>Komentar di Artikel Saya</p></div>
    </div>

    <div class="card-grid">
        <a class="form-card" href="/blog/author/posts.php"><h3>Kelola Artikel Saya</h3><p>Tambah, edit, hapus artikel milik Anda.</p></a>
        <a class="form-card" href="/blog/index.php"><h3>Lihat Blog</h3><p>Review hasil publish di halaman publik.</p></a>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>