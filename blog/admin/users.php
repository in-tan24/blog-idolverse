<?php
require_once __DIR__ . '/../includes/helpers.php';
requireRole(['admin']);

$adminId = (int)currentUser()['id'];
$error = '';

if (isset($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role IN ('author', 'user')");
    $stmt->execute([$deleteId]);
    header('Location: /blog/admin/users.php?msg=deleted');
    exit;
}

$editData = null;
if (isset($_GET['edit'])) {
    $editId = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT id, full_name, username, email, role FROM users WHERE id = ? AND role IN ('author', 'user') LIMIT 1");
    $stmt->execute([$editId]);
    $editData = $stmt->fetch();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $role = trim($_POST['role'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($fullName === '' || $username === '' || $email === '' || !in_array($role, ['author', 'user'], true)) {
        $error = 'Nama, username, email, dan role wajib diisi dengan benar.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } else {
        if ($id > 0) {
            $check = $pdo->prepare('SELECT COUNT(*) FROM users WHERE (username = ? OR email = ?) AND id != ?');
            $check->execute([$username, $email, $id]);
            if ((int)$check->fetchColumn() > 0) {
                $error = 'Username atau email sudah digunakan.';
            } else {
                if ($password !== '') {
                    if (strlen($password) < 6) {
                        $error = 'Password minimal 6 karakter.';
                    } else {
                        $hash = password_hash($password, PASSWORD_DEFAULT);
                        $update = $pdo->prepare("UPDATE users SET full_name=?, username=?, email=?, role=?, password=? WHERE id=? AND role IN ('author','user')");
                        $update->execute([$fullName, $username, $email, $role, $hash, $id]);
                    }
                } else {
                    $update = $pdo->prepare("UPDATE users SET full_name=?, username=?, email=?, role=? WHERE id=? AND role IN ('author','user')");
                    $update->execute([$fullName, $username, $email, $role, $id]);
                }

                if ($error === '') {
                    header('Location: /blog/admin/users.php?msg=updated');
                    exit;
                }
            }
        } else {
            if (strlen($password) < 6) {
                $error = 'Password minimal 6 karakter.';
            } else {
                $check = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
                $check->execute([$username, $email]);
                if ((int)$check->fetchColumn() > 0) {
                    $error = 'Username atau email sudah digunakan.';
                } else {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $insert = $pdo->prepare('INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, ?)');
                    $insert->execute([$fullName, $username, $email, $hash, $role]);
                    header('Location: /blog/admin/users.php?msg=created');
                    exit;
                }
            }
        }
    }
}

$users = $pdo->query("SELECT id, full_name, username, email, role, created_at FROM users WHERE role IN ('author', 'user') ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Kelola Akun';
include __DIR__ . '/../includes/header.php';
?>
<div class="container" style="max-width: 1100px;">
    <h1>Kelola Author & User</h1>
    <p class="muted">Admin dapat menambah, mengubah, dan menghapus akun author maupun user.</p>

    <?php if (($_GET['msg'] ?? '') === 'created'): ?><div class="alert alert-success">Akun berhasil ditambahkan.</div><?php endif; ?>
    <?php if (($_GET['msg'] ?? '') === 'updated'): ?><div class="alert alert-success">Akun berhasil diperbarui.</div><?php endif; ?>
    <?php if (($_GET['msg'] ?? '') === 'deleted'): ?><div class="alert alert-success">Akun berhasil dihapus.</div><?php endif; ?>
    <?php if ($error !== ''): ?><div class="alert alert-error"><?= e($error); ?></div><?php endif; ?>

    <div class="form-card" style="margin-bottom:16px;">
        <h2><?= $editData ? 'Edit Akun' : 'Tambah Akun Baru'; ?></h2>
        <form method="post">
            <input type="hidden" name="id" value="<?= (int)($editData['id'] ?? 0); ?>">

            <label>Nama Lengkap</label>
            <input type="text" name="full_name" required value="<?= e($editData['full_name'] ?? ''); ?>">

            <label>Username</label>
            <input type="text" name="username" required value="<?= e($editData['username'] ?? ''); ?>">

            <label>Email</label>
            <input type="email" name="email" required value="<?= e($editData['email'] ?? ''); ?>">

            <label>Role</label>
            <select name="role" required>
                <option value="">Pilih role</option>
                <option value="author" <?= (($editData['role'] ?? '') === 'author') ? 'selected' : ''; ?>>Author</option>
                <option value="user" <?= (($editData['role'] ?? '') === 'user') ? 'selected' : ''; ?>>User</option>
            </select>

            <label>Password <?= $editData ? '(kosongkan jika tidak diubah)' : ''; ?></label>
            <input type="password" name="password" <?= $editData ? '' : 'required'; ?>>

            <button class="btn" type="submit"><?= $editData ? 'Update Akun' : 'Tambah Akun'; ?></button>
            <?php if ($editData): ?><a class="btn btn-ghost" href="/blog/admin/users.php">Batal</a><?php endif; ?>
        </form>
    </div>

    <div class="table-wrap">
        <table class="table">
            <thead><tr><th>Nama</th><th>Username</th><th>Email</th><th>Role</th><th>Dibuat</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= e($u['full_name']); ?></td>
                    <td><?= e($u['username']); ?></td>
                    <td><?= e($u['email']); ?></td>
                    <td><span class="badge"><?= e(ucfirst($u['role'])); ?></span></td>
                    <td><?= date('d M Y', strtotime($u['created_at'])); ?></td>
                    <td>
                        <a class="btn btn-sm" href="/blog/admin/users.php?edit=<?= (int)$u['id']; ?>">Edit</a>
                        <a class="btn btn-sm btn-danger" href="/blog/admin/users.php?delete=<?= (int)$u['id']; ?>" onclick="return confirm('Hapus akun ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../includes/footer.php'; ?>