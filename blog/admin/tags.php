<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare('DELETE FROM tags WHERE id = ?')->execute([$id]);
    header('Location: /blog/admin/tags.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    if ($name !== '') {
        $slug = uniqueSlug($pdo, 'tags', slugify($name), $id ?: null);
        if ($id > 0) {
            $pdo->prepare('UPDATE tags SET name=?, slug=? WHERE id=?')->execute([$name, $slug, $id]);
        } else {
            $pdo->prepare('INSERT INTO tags (name, slug) VALUES (?, ?)')->execute([$name, $slug]);
        }
    }
    header('Location: /blog/admin/tags.php');
    exit;
}

$edit = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare('SELECT * FROM tags WHERE id = ?');
    $stmt->execute([(int)$_GET['edit']]);
    $edit = $stmt->fetch();
}

$tags = $pdo->query('SELECT * FROM tags ORDER BY name')->fetchAll();
$pageTitle = 'Kelola Tag';
include __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width:900px;">
    <h1>Kelola Tag</h1>
    <div class="form-card">
        <form method="post" style="display:flex; gap:10px; align-items:flex-end; flex-wrap:wrap;">
            <input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0); ?>">
            <div style="flex:1;min-width:220px;">
                <label>Nama Tag</label>
                <input type="text" name="name" value="<?= e($edit['name'] ?? ''); ?>" required>
            </div>
            <button class="btn" type="submit"><?= $edit ? 'Update' : 'Tambah'; ?></button>
            <?php if ($edit): ?><a class="btn btn-ghost" href="/blog/admin/tags.php">Batal</a><?php endif; ?>
        </form>
    </div>

    <div class="table-wrap" style="margin-top:16px;">
        <table class="table">
            <thead><tr><th>Nama</th><th>Slug</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($tags as $tag): ?>
                <tr>
                    <td><?= e($tag['name']); ?></td>
                    <td><?= e($tag['slug']); ?></td>
                    <td>
                        <a class="btn btn-sm" href="/blog/admin/tags.php?edit=<?= (int)$tag['id']; ?>">Edit</a>
                        <a class="btn btn-sm btn-danger" href="/blog/admin/tags.php?delete=<?= (int)$tag['id']; ?>" onclick="return confirm('Hapus tag ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>