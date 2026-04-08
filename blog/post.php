<?php
require_once __DIR__ . '/includes/helpers.php';

$slug = trim($_GET['slug'] ?? '');
$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.slug AS category_slug, u.full_name AS author_name
FROM posts p
JOIN categories c ON c.id = p.category_id
JOIN users u ON u.id = p.user_id
WHERE p.slug = ? AND p.is_published = 1 LIMIT 1");
$stmt->execute([$slug]);
$post = $stmt->fetch();

if (!$post) {
    http_response_code(404);
    echo 'Artikel tidak ditemukan.';
    exit;
}

$pageTitle = $post['title'] . ' - Idolverse';
$success = '';
$error = '';
$current = currentUser();
$isLoggedUser = isLoggedIn();
$userRole = $current['role'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim((string)($current['full_name'] ?? ''));
    $email = trim((string)($current['email'] ?? ''));
    $message = trim($_POST['message'] ?? '');

    if (!$isLoggedUser) {
        $error = 'Silakan login sebagai user untuk menulis komentar.';
    } elseif ($userRole !== 'user') {
        $error = 'Hanya akun user yang bisa menulis komentar. Admin membalas dari Dashboard Admin.';
    } elseif ($name === '' || $email === '' || $message === '') {
        $error = 'Komentar tidak boleh kosong.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        $insert = $pdo->prepare('INSERT INTO comments (post_id, name, email, message, is_approved) VALUES (?, ?, ?, ?, 1)');
        $insert->execute([(int)$post['id'], $name, $email, $message]);
        $success = 'Komentar berhasil dikirim.';
    }
}

$commentStmt = $pdo->prepare('SELECT * FROM comments WHERE post_id = ? AND is_approved = 1 ORDER BY created_at DESC');
$commentStmt->execute([(int)$post['id']]);
$comments = $commentStmt->fetchAll();
$tags = postTags($pdo, (int)$post['id']);

include __DIR__ . '/includes/header.php';
?>
<div class="container">
    <article class="form-card">
        <p><a class="badge" href="/blog/category.php?slug=<?= e($post['category_slug']); ?>"><?= e($post['category_name']); ?></a></p>
        <h1><?= e($post['title']); ?></h1>
        <div class="meta">
            <span><?= date('d M Y H:i', strtotime($post['created_at'])); ?></span>
            <span>Penulis: <?= e($post['author_name']); ?></span>
        </div>
        <?php if (!empty($post['image_url'])): ?>
            <img src="<?= e($post['image_url']); ?>" alt="<?= e($post['title']); ?>" style="width:100%;border-radius:12px;max-height:420px;object-fit:cover;">
        <?php endif; ?>
        <p style="margin-top:16px;line-height:1.75;white-space:pre-line"><?= e($post['content']); ?></p>

        <div class="tags">
            <?php foreach ($tags as $tag): ?>
                <a class="tag" href="/blog/tag.php?slug=<?= e($tag['slug']); ?>">#<?= e($tag['name']); ?></a>
            <?php endforeach; ?>
        </div>
    </article>

    <section class="form-card" style="margin-top:20px;">
        <h2>Komentar (<?= count($comments); ?>)</h2>
        <?php if ($success): ?><div class="alert alert-success"><?= e($success); ?></div><?php endif; ?>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error); ?></div><?php endif; ?>

        <?php foreach ($comments as $comment): ?>
            <div class="comment">
                <strong><?= e($comment['name']); ?></strong>
                <div class="muted"><?= date('d M Y H:i', strtotime($comment['created_at'])); ?></div>
                <p><?= nl2br(e($comment['message'])); ?></p>
            </div>
        <?php endforeach; ?>

        <?php if (!$isLoggedUser): ?>
            <div class="alert alert-error">Untuk komentar, silakan <a href="/blog/login.php">login</a> atau <a href="/blog/register.php">daftar akun user</a> terlebih dulu.</div>
        <?php elseif ($userRole !== 'user'): ?>
            <div class="alert alert-error">Role <strong><?= e($userRole); ?></strong> tidak bisa post komentar di halaman user. Gunakan dashboard masing-masing.</div>
        <?php else: ?>
            <h3>Tulis Komentar</h3>
            <form method="post">
                <p class="muted">Komentar sebagai <strong><?= e($current['full_name']); ?></strong> (<?= e($current['email']); ?>)</p>
                <label>Komentar</label>
                <textarea name="message" required></textarea>
                <button class="btn" type="submit">Kirim</button>
            </form>
        <?php endif; ?>
    </section>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
