<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$isEdit = $id > 0;
$userId = (int)currentUser()['id'];

$categories = $pdo->query('SELECT id, name FROM categories ORDER BY name')->fetchAll();
$tags = $pdo->query('SELECT id, name FROM tags ORDER BY name')->fetchAll();

$data = [
    'title' => '',
    'category_id' => '',
    'excerpt' => '',
    'content' => '',
    'image_url' => '',
    'is_published' => 1
];
$selectedTags = [];
$error = '';

if ($isEdit) {
    $stmt = $pdo->prepare('SELECT * FROM posts WHERE id = ? LIMIT 1');
    $stmt->execute([$id]);
    $post = $stmt->fetch();
    if (!$post) {
        echo 'Artikel tidak ditemukan.';
        exit;
    }
    $data = $post;
    $tagStmt = $pdo->prepare('SELECT tag_id FROM post_tags WHERE post_id = ?');
    $tagStmt->execute([$id]);
    $selectedTags = array_column($tagStmt->fetchAll(), 'tag_id');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $imageUrl = (string)($data['image_url'] ?? '');
    $isPublished = isset($_POST['is_published']) ? 1 : 0;
    $tagIds = $_POST['tag_ids'] ?? [];

    if ($title === '' || $categoryId <= 0 || $content === '') {
        $error = 'Judul, kategori, dan isi artikel wajib diisi.';
    } else {
        [$uploadOk, $uploadedImageUrl, $uploadError] = uploadPostImage('image_file', $imageUrl);
        if (!$uploadOk) {
            $error = $uploadError;
        } else {
            $imageUrl = $uploadedImageUrl;
        }
    }

    if ($error === '') {
        $baseSlug = slugify($title);
        $slug = uniqueSlug($pdo, 'posts', $baseSlug, $isEdit ? $id : null);

        if ($isEdit) {
            $update = $pdo->prepare('UPDATE posts SET category_id=?, title=?, slug=?, excerpt=?, content=?, image_url=?, is_published=? WHERE id=?');
            $update->execute([$categoryId, $title, $slug, $excerpt, $content, $imageUrl, $isPublished, $id]);

            $pdo->prepare('DELETE FROM post_tags WHERE post_id=?')->execute([$id]);
            $insTag = $pdo->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)');
            foreach ($tagIds as $tagId) {
                $insTag->execute([$id, (int)$tagId]);
            }
        } else {
            $insert = $pdo->prepare('INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, image_url, is_published) VALUES (?,?,?,?,?,?,?,?)');
            $insert->execute([$userId, $categoryId, $title, $slug, $excerpt, $content, $imageUrl, $isPublished]);
            $postId = (int)$pdo->lastInsertId();

            $insTag = $pdo->prepare('INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)');
            foreach ($tagIds as $tagId) {
                $insTag->execute([$postId, (int)$tagId]);
            }
        }

        header('Location: /blog/admin/posts.php');
        exit;
    }
}

$pageTitle = $isEdit ? 'Edit Artikel' : 'Tambah Artikel';
include __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width: 900px;">
    <h1><?= $isEdit ? 'Edit Artikel' : 'Tambah Artikel'; ?></h1>
    <div class="form-card">
        <?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error); ?></div><?php endif; ?>
        <form method="post" enctype="multipart/form-data">
            <label>Judul</label>
            <input type="text" name="title" value="<?= e((string)$data['title']); ?>" required>

            <label>Kategori</label>
            <select name="category_id" required>
                <option value="">Pilih kategori</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= (int)$category['id']; ?>" <?= (int)$data['category_id'] === (int)$category['id'] ? 'selected' : ''; ?>>
                        <?= e($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Tag</label>
            <select name="tag_ids[]" multiple size="6">
                <?php foreach ($tags as $tag): ?>
                    <option value="<?= (int)$tag['id']; ?>" <?= in_array($tag['id'], $selectedTags, true) ? 'selected' : ''; ?>>
                        <?= e($tag['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Ringkasan</label>
            <textarea name="excerpt"><?= e((string)$data['excerpt']); ?></textarea>

            <label>Isi Artikel</label>
            <textarea name="content" required><?= e((string)$data['content']); ?></textarea>

            <label>Upload Gambar (jpg, jpeg, png, jfif, oif - maks 5 MB)</label>
            <input type="file" name="image_file" accept=".jpg,.jpeg,.png,.jfif,.oif,image/jpeg,image/png">
            <?php if (!empty($data['image_url'])): ?>
                <p class="muted">Gambar saat ini:</p>
                <img src="<?= e((string)$data['image_url']); ?>" alt="Gambar artikel" style="max-width:220px;border-radius:10px;border:1px solid #ddd;">
            <?php endif; ?>

            <label><input type="checkbox" name="is_published" <?= (int)$data['is_published'] === 1 ? 'checked' : ''; ?>> Publish</label>
            <button class="btn" type="submit">Simpan</button>
            <a class="btn btn-ghost" href="/blog/admin/posts.php">Kembali</a>
        </form>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>
