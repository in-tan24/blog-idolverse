<?php
require_once __DIR__ . '/includes/helpers.php';

if (isLoggedIn()) {
    header('Location: /blog/user/dashboard.php');
    exit;
}

$pageTitle = 'Daftar User - Idolverse';
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($fullName === '' || $username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        $error = 'Semua field wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } elseif ($password !== $confirmPassword) {
        $error = 'Konfirmasi password tidak sama.';
    } else {
        $check = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ? OR email = ?');
        $check->execute([$username, $email]);
        if ((int)$check->fetchColumn() > 0) {
            $error = 'Username atau email sudah digunakan.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $insert = $pdo->prepare("INSERT INTO users (full_name, username, email, password, role) VALUES (?, ?, ?, ?, 'user')");
            $insert->execute([$fullName, $username, $email, $hash]);
            $success = 'Registrasi berhasil. Silakan login.';
        }
    }
}

include __DIR__ . '/includes/header.php';
?>
<div class="container" style="max-width:620px;">
    <div class="form-card">
        <h1>Daftar Akun User</h1>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error); ?></div><?php endif; ?>
        <?php if ($success): ?><div class="alert alert-success"><?= e($success); ?></div><?php endif; ?>

        <form method="post">
            <label>Nama Lengkap</label>
            <input type="text" name="full_name" required>

            <label>Username</label>
            <input type="text" name="username" required>

            <label>Email</label>
            <input type="email" name="email" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Konfirmasi Password</label>
            <input type="password" name="confirm_password" required>

            <button class="btn" type="submit">Daftar</button>
            <a class="btn btn-ghost" href="/blog/login.php">Login</a>
        </form>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
