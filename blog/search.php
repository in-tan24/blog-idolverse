<?php
require_once __DIR__ . '/includes/helpers.php';
$pageTitle = 'Cari Artikel - Idolverse';

$q = trim($_GET['q'] ?? '');
$posts = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT p.*, c.name AS category_name FROM posts p JOIN categories c ON c.id = p.category_id
    WHERE p.is_published = 1 AND (p.title LIKE ? OR p.content LIKE ? OR p.excerpt LIKE ?) ORDER BY p.created_at DESC");
    $keyword = '%' . $q . '%';
    $stmt->execute([$keyword, $keyword, $keyword]);
    $posts = $stmt->fetchAll();
}

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <h1>Cari Artikel</h1>
    <form class="form-card" method="get">
        <label for="q">Kata kunci</label>
        <input type="text" id="q" name="q" value="<?= e($q); ?>" placeholder="Contoh: Idol K-Pop, Review Album" required>
        <button class="btn" type="submit">Cari</button>
    </form>

    <?php if ($q !== ''): ?>
        <h2>Hasil untuk "<?= e($q); ?>"</h2>
        <?php if (!$posts): ?>
            <p class="muted">Belum ada artikel yang cocok.</p>
        <?php else: ?>
            <div class="card-grid">
                <?php foreach ($posts as $post): ?>
                    <article class="card">
                        <img src="<?= e($post['image_url'] ?: 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=1200&q=80'); ?>" alt="<?= e($post['title']); ?>">
                        <div class="card-body">
                            <span class="badge"><?= e($post['category_name']); ?></span>
                            <h3><a href="/blog/post.php?slug=<?= e($post['slug']); ?>"><?= e($post['title']); ?></a></h3>
                            <p><?= e($post['excerpt'] ?? ''); ?></p>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
