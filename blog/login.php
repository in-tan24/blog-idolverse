<?php
require_once __DIR__ . '/includes/helpers.php';

if (isLoggedIn()) {
    $role = currentUser()['role'] ?? '';
    if ($role === 'admin') {
        $dashboard = '/blog/admin/dashboard.php';
    } elseif ($role === 'author') {
        $dashboard = '/blog/author/dashboard.php';
    } else {
        $dashboard = '/blog/user/dashboard.php';
    }
    header('Location: ' . $dashboard);
    exit;
}

$pageTitle = 'Login - Idolverse';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = ? LIMIT 1');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'full_name' => $user['full_name'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => $user['role']
        ];

        if ($user['role'] === 'admin') {
            $dashboard = '/blog/admin/dashboard.php';
        } elseif ($user['role'] === 'author') {
            $dashboard = '/blog/author/dashboard.php';
        } else {
            $dashboard = '/blog/user/dashboard.php';
        }
        header('Location: ' . $dashboard);
        exit;
    }

    $error = 'Username atau password salah.';
}

include __DIR__ . '/includes/header.php';
?>
<div class="container" style="max-width:560px;">
    <div class="form-card">
        <h1>Login Akun</h1>
        <p class="muted">Masuk sebagai user untuk komentar, atau sebagai admin/author untuk mengelola blog.</p>
        <?php if ($error): ?><div class="alert alert-error"><?= e($error); ?></div><?php endif; ?>
        <form method="post">
            <label>Username</label>
            <input type="text" name="username" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button class="btn" type="submit">Login</button>
        </form>
        <p class="muted">Belum punya akun user? <a href="/blog/register.php">Daftar di sini</a>.</p>
       
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
