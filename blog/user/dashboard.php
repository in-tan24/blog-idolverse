<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['user']);

$user = currentUser();
$stmt = $pdo->prepare('SELECT COUNT(*) FROM comments WHERE email = ?');
$stmt->execute([$user['email']]);
$totalComments = (int)$stmt->fetchColumn();

$pageTitle = 'Dashboard User';
include __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width:900px;">
    <h1>Dashboard User</h1>
    <p class="muted">Halo, <?= e($user['full_name']); ?>. Sekarang kamu bisa komentar di artikel.</p>

    <div class="stats">
        <div class="stat">
            <h3><?= $totalComments; ?></h3>
            <p>Total komentar kamu</p>
        </div>
    </div>

    <div class="card-grid">
        <a class="form-card" href="/blog/index.php">
            <h3>Jelajahi Artikel</h3>
            <p>Baca artikel terbaru dan berikan komentar.</p>
        </a>
        <a class="form-card" href="/blog/search.php">
            <h3>Cari Topik</h3>
            <p>Temukan artikel berdasarkan kata kunci.</p>
        </a>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>