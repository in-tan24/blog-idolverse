<?php
require_once __DIR__ . '/includes/helpers.php';
$slug = trim($_GET['slug'] ?? '');

$stmt = $pdo->prepare('SELECT * FROM categories WHERE slug = ? LIMIT 1');
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) {
    http_response_code(404);
    echo 'Kategori tidak ditemukan.';
    exit;
}

$pageTitle = 'Kategori: ' . $category['name'];
$postStmt = $pdo->prepare("SELECT p.*, u.full_name AS author_name FROM posts p JOIN users u ON u.id = p.user_id WHERE p.category_id = ? AND p.is_published = 1 ORDER BY p.created_at DESC");
$postStmt->execute([(int)$category['id']]);
$posts = $postStmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1>Kategori: <?= e($category['name']); ?></h1>
    <?php if (!$posts): ?>
        <p class="muted">Belum ada artikel pada kategori ini.</p>
    <?php endif; ?>
    <div class="card-grid">
        <?php foreach ($posts as $post): ?>
        <article class="card">
            <img src="<?= e($post['image_url'] ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($post['title']); ?>">
            <div class="card-body">
                <div class="muted"><?= date('d M Y', strtotime($post['created_at'])); ?> · <?= e($post['author_name']); ?></div>
                <h3><a href="/blog/post.php?slug=<?= e($post['slug']); ?>"><?= e($post['title']); ?></a></h3>
                <p><?= e($post['excerpt'] ?? ''); ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>