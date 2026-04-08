<?php
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Semua Artikel - Idolverse';

$posts = $pdo->query("SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.full_name AS author_name,
(SELECT COUNT(*) FROM comments cm WHERE cm.post_id = p.id AND cm.is_approved = 1) AS comments_count
FROM posts p
JOIN categories c ON c.id = p.category_id
JOIN users u ON u.id = p.user_id
WHERE p.is_published = 1
ORDER BY p.created_at DESC")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1>Semua Artikel</h1>
    <p class="muted">Temukan artikel terbaik dari berbagai topik.</p>
    <div class="card-grid">
        <?php foreach ($posts as $post): ?>
            <?php $tags = postTags($pdo, (int)$post['id']); ?>
            <article class="card">
                <img src="<?= e($post['image_url'] ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($post['title']); ?>">
                <div class="card-body">
                    <div class="meta">
                        <span><?= date('d M Y', strtotime($post['created_at'])); ?></span>
                        <span>By <?= e($post['author_name']); ?></span>
                        <span><?= (int)$post['comments_count']; ?> komentar</span>
                    </div>
                    <h3><a href="/blog/post.php?slug=<?= e($post['slug']); ?>"><?= e($post['title']); ?></a></h3>
                    <p><?= e($post['excerpt'] ?? ''); ?></p>
                    <p><a class="badge" href="/blog/category.php?slug=<?= e($post['category_slug']); ?>"><?= e($post['category_name']); ?></a></p>
                    <div class="tags">
                        <?php foreach ($tags as $tag): ?>
                            <a class="tag" href="/blog/tag.php?slug=<?= e($tag['slug']); ?>">#<?= e($tag['name']); ?></a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
