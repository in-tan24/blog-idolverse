<?php
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Landing Page - Idolverse';

$featured = $pdo->query("SELECT p.*, c.name AS category_name, u.full_name AS author_name
FROM posts p
JOIN categories c ON c.id = p.category_id
JOIN users u ON u.id = p.user_id
WHERE p.is_published = 1
ORDER BY p.created_at DESC
LIMIT 4")->fetchAll();

$latest = $pdo->query("SELECT p.*, c.name AS category_name
FROM posts p
JOIN categories c ON c.id = p.category_id
WHERE p.is_published = 1
ORDER BY p.created_at DESC
LIMIT 6")->fetchAll();

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <section class="hero">
        <h1>Segalanya Tentang Idola, Dari Fans Untuk Fans</h1>
        <p>Idolverse adalah ruang kreatif untuk berbagi momen spesial, berita terkini, dan dedikasi terbaik untuk idola favoritmu. Jelajahi konten eksklusif seputar musik, behind the scene, dan gaya hidup idola</p>
        <a class="btn" href="/blog/index.php">Mulai Baca Artikel</a>
    </section>

    <?php if ($featured): ?>
    <section class="carousel" data-carousel>
        <div class="slides">
            <?php foreach ($featured as $item): ?>
            <article class="slide">
                <img src="<?= e($item['image_url'] ?: 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($item['title']); ?>">
                <div class="slide-content">
                    <span class="badge"><?= e($item['category_name']); ?></span>
                    <h2><?= e($item['title']); ?></h2>
                    <p><?= e($item['excerpt'] ?? ''); ?></p>
                    <a class="btn" href="/blog/post.php?slug=<?= e($item['slug']); ?>">Baca Selengkapnya</a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <button class="carousel-btn prev" type="button">&#10094;</button>
        <button class="carousel-btn next" type="button">&#10095;</button>
    </section>
    <?php endif; ?>

    <section>
        <h2>Artikel Terbaru</h2>
        <div class="card-grid">
            <?php foreach ($latest as $post): ?>
            <article class="card">
                <img src="<?= e($post['image_url'] ?: 'https://images.unsplash.com/photo-1518773553398-650c184e0bb3?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($post['title']); ?>">
                <div class="card-body">
                    <span class="badge"><?= e($post['category_name']); ?></span>
                    <h3><a href="/blog/post.php?slug=<?= e($post['slug']); ?>"><?= e($post['title']); ?></a></h3>
                    <p class="muted"><?= e($post['excerpt'] ?? ''); ?></p>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </section>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
