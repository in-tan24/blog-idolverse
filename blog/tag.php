<?php
require_once __DIR__ . '/includes/helpers.php';
$slug = trim($_GET['slug'] ?? '');

$stmt = $pdo->prepare('SELECT * FROM tags WHERE slug = ? LIMIT 1');
$stmt->execute([$slug]);
$tag = $stmt->fetch();

if (!$tag) {
    http_response_code(404);
    echo 'Tag tidak ditemukan.';
    exit;
}

$pageTitle = 'Tag: ' . $tag['name'];
$postStmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM post_tags pt
JOIN posts p ON p.id = pt.post_id
JOIN categories c ON c.id = p.category_id
WHERE pt.tag_id = ? AND p.is_published = 1 ORDER BY p.created_at DESC");
$postStmt->execute([(int)$tag['id']]);
$posts = $postStmt->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1>Tag: #<?= e($tag['name']); ?></h1>
    <?php if (!$posts): ?>
        <p class="muted">Belum ada artikel dengan tag ini.</p>
    <?php endif; ?>
    <div class="card-grid">
        <?php foreach ($posts as $post): ?>
        <article class="card">
            <img src="<?= e($post['image_url'] ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($post['title']); ?>">
            <div class="card-body">
                <p><span class="badge"><?= e($post['category_name']); ?></span></p>
                <h3><a href="/blog/post.php?slug=<?= e($post['slug']); ?>"><?= e($post['title']); ?></a></h3>
                <p><?= e($post['excerpt'] ?? ''); ?></p>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>