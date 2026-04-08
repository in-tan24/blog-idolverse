<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM categories WHERE id = ?')->execute([$id]);
    header('Location: /blog/admin/categories.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $slug = uniqueSlug($pdo, 'categories', slugify($name), $id ?: null);
        if ($id > 0) {
            $pdo->prepare('UPDATE categories SET name=?, slug=? WHERE id=?')->execute([$name, $slug, $id]);
        } else {
            $pdo->prepare('INSERT INTO categories (name, slug) VALUES (?, ?)')->execute([$name, $slug]);
        }
    }
    header('Location: /blog/admin/categories.php');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();
$pageTitle = 'Kelola Kategori';
include __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width:900px;">
    <h1>Kelola Kategori</h1>
    <div class="form-card">
        <form method="post" style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0); ?>">
            <div style="flex:1;min-width:220px;">
                <label>Nama Kategori</label>
                <input type="text" name="name" value="<?= e($edit['name'] ?? ''); ?>" required>
            </div>
            <button class="btn" type="submit"><?= $edit ? 'Update' : 'Tambah'; ?></button>
            <?php if ($edit): ?><a class="btn btn-ghost" href="/blog/admin/categories.php">Batal</a><?php endif; ?>
        </form>
    </div>

    <div class="table-wrap" style="margin-top:16px;">
        <table class="table">
            <thead><tr><th>Nama</th><th>Slug</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= e($category['name']); ?></td>
                    <td><?= e($category['slug']); ?></td>
                    <td>
                        <a class="btn btn-sm" href="/blog/admin/categories.php?edit=<?= (int)$category['id']; ?>">Edit</a>
                        <a class="btn btn-sm btn-danger" href="/blog/admin/categories.php?delete=<?= (int)$category['id']; ?>" onclick="return confirm('Hapus kategori ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>