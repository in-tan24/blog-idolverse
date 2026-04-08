<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/auth.php';

function slugify(string $text): string {
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'artikel';
}

function uniqueSlug(PDO $pdo, string $table, string $baseSlug, ?int $excludeId = null): string {
    $slug = $baseSlug;
    $counter = 1;

    while (true) {
        if ($excludeId) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE slug = ? AND id != ?");
            $stmt->execute([$slug, $excludeId]);
        } else {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM {$table} WHERE slug = ?");
            $stmt->execute([$slug]);
        }

        if ((int)$stmt->fetchColumn() === 0) {
            return $slug;
        }

        $slug = $baseSlug . '-' . $counter;
        $counter++;
    }
}

function postTags(PDO $pdo, int $postId): array {
    $stmt = $pdo->prepare('SELECT t.id, t.name, t.slug FROM post_tags pt JOIN tags t ON t.id = pt.tag_id WHERE pt.post_id = ? ORDER BY t.name');
    $stmt->execute([$postId]);
    return $stmt->fetchAll();
}

function uploadPostImage(string $fieldName, ?string $currentImageUrl = null): array {
    if (!isset($_FILES[$fieldName]) || !is_array($_FILES[$fieldName])) {
        return [true, $currentImageUrl ?? '', ''];
    }

    $file = $_FILES[$fieldName];
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
        return [true, $currentImageUrl ?? '', ''];
    }

    if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
        return [false, $currentImageUrl ?? '', 'Upload gambar gagal.'];
    }

    $maxSize = 5 * 1024 * 1024;
    if (($file['size'] ?? 0) > $maxSize) {
        return [false, $currentImageUrl ?? '', 'Ukuran gambar maksimal 5 MB.'];
    }

    $originalName = (string)($file['name'] ?? '');
    $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'jfif', 'oif'];
    if (!in_array($extension, $allowedExtensions, true)) {
        return [false, $currentImageUrl ?? '', 'Format gambar harus jpg, jpeg, png, jfif, atau oif.'];
    }

    $tmpPath = (string)($file['tmp_name'] ?? '');
    $imageInfo = @getimagesize($tmpPath);
    if ($imageInfo === false) {
        return [false, $currentImageUrl ?? '', 'File yang diunggah bukan gambar valid.'];
    }

    $uploadDir = __DIR__ . '/../uploads';
    if (!is_dir($uploadDir) && !mkdir($uploadDir, 0775, true) && !is_dir($uploadDir)) {
        return [false, $currentImageUrl ?? '', 'Folder upload tidak bisa dibuat.'];
    }

    $newFileName = 'post-' . date('YmdHis') . '-' . bin2hex(random_bytes(4)) . '.' . $extension;
    $targetPath = $uploadDir . '/' . $newFileName;

    if (!move_uploaded_file($tmpPath, $targetPath)) {
        return [false, $currentImageUrl ?? '', 'Gagal menyimpan gambar ke server.'];
    }

    return [true, '/blog/uploads/' . $newFileName, ''];
}
?>
