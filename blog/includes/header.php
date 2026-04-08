<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentPath = $_SERVER['PHP_SELF'] ?? '';
$user = $_SESSION['user'] ?? null;
$isAdminArea = str_contains($currentPath, '/admin/');

$navCategories = [];
$navTrending = [];
if (!$isAdminArea && isset($pdo)) {
    $navCategories = $pdo->query("SELECT c.name, c.slug, COUNT(p.id) AS total_posts
    FROM categories c
    LEFT JOIN posts p ON p.category_id = c.id AND p.is_published = 1
    GROUP BY c.id, c.name, c.slug
    ORDER BY c.name ASC
    LIMIT 8")->fetchAll();

    $navTrending = $pdo->query("SELECT t.name, t.slug, COUNT(pt.post_id) AS total_posts
    FROM tags t
    JOIN post_tags pt ON pt.tag_id = t.id
    JOIN posts p ON p.id = pt.post_id
    WHERE p.is_published = 1
    GROUP BY t.id, t.name, t.slug
    ORDER BY total_posts DESC, t.name ASC
    LIMIT 8")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Blog Dinamis'); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Nunito:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/blog/assets/css/style.css">
</head>
<body class="<?= $isAdminArea ? 'admin-area' : ''; ?>">
<header class="site-header">
    <div class="container nav-wrap">
        <a class="brand" href="/blog/landing.php">Idolverse <?= $isAdminArea ? '<span class="admin-badge">Admin Area</span>' : ''; ?></a>
        <nav>
            <?php if ($user && $user['role'] === 'admin' && $isAdminArea): ?>
                <a href="/blog/admin/dashboard.php" class="<?= str_contains($currentPath, '/admin/dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <a href="/blog/admin/posts.php" class="<?= str_contains($currentPath, '/admin/posts.php') || str_contains($currentPath, '/admin/post_form.php') ? 'active' : ''; ?>">Artikel</a>
                <a href="/blog/admin/categories.php" class="<?= str_contains($currentPath, '/admin/categories.php') ? 'active' : ''; ?>">Kategori</a>
                <a href="/blog/admin/tags.php" class="<?= str_contains($currentPath, '/admin/tags.php') ? 'active' : ''; ?>">Tag</a>
                <a href="/blog/admin/comments.php" class="<?= str_contains($currentPath, '/admin/comments.php') ? 'active' : ''; ?>">Komentar</a>
                <a href="/blog/admin/users.php" class="<?= str_contains($currentPath, '/admin/users.php') ? 'active' : ''; ?>">Akun</a>
                <a href="/blog/landing.php">Lihat Landing</a>
                <a href="/blog/logout.php" class="btn btn-sm btn-ghost">Logout</a>
            <?php else: ?>
                <a href="/blog/landing.php" class="<?= str_contains($currentPath, 'landing.php') ? 'active' : ''; ?>">Landing</a>
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropbtn" aria-expanded="false" aria-controls="menu-kategori">Kategori</button>
                    <div id="menu-kategori" class="nav-menu" hidden>
                        <?php foreach ($navCategories as $item): ?>
                            <a href="/blog/category.php?slug=<?= e($item['slug']); ?>">
                                <span><?= e($item['name']); ?></span>
                                <span><?= (int)$item['total_posts']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="nav-dropdown">
                    <button type="button" class="nav-dropbtn" aria-expanded="false" aria-controls="menu-trending">Trending</button>
                    <div id="menu-trending" class="nav-menu" hidden>
                        <?php foreach ($navTrending as $item): ?>
                            <a href="/blog/tag.php?slug=<?= e($item['slug']); ?>">
                                <span>#<?= e($item['name']); ?></span>
                                <span><?= (int)$item['total_posts']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <a href="/blog/index.php" class="<?= str_contains($currentPath, 'index.php') ? 'active' : ''; ?>">Artikel</a>
                <a href="/blog/search.php">Cari</a>
                <?php if (!$user): ?>
                    <a href="/blog/login.php" class="btn btn-sm">Login</a>
                    <a href="/blog/register.php" class="btn btn-sm btn-ghost">Daftar</a>
                <?php else: ?>
                    <?php if ($user['role'] === 'admin'): ?>
                        <a href="/blog/admin/dashboard.php">Dashboard</a>
                    <?php elseif ($user['role'] === 'author'): ?>
                        <a href="/blog/author/dashboard.php">Dashboard</a>
                    <?php else: ?>
                        <a href="/blog/user/dashboard.php">Akun Saya</a>
                    <?php endif; ?>
                    <a href="/blog/logout.php" class="btn btn-sm btn-ghost">Logout</a>
                <?php endif; ?>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main class="page">
