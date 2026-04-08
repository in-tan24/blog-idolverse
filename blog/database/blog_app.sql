CREATE DATABASE IF NOT EXISTS blog_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE blog_app;

DROP TABLE IF EXISTS post_tags;
DROP TABLE IF EXISTS comments;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS tags;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'author', 'user') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tags (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    title VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    image_url VARCHAR(255) DEFAULT NULL,
    is_published TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT
);

CREATE TABLE post_tags (
    post_id INT NOT NULL,
    tag_id INT NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (tag_id) REFERENCES tags(id) ON DELETE CASCADE
);

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    is_approved TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE
);

INSERT INTO users (full_name, username, email, password, role) VALUES
('Administrator Blog', 'admin', 'admin@blog.local', '$2y$10$e1vMoB73slWjQ57fdHDrI.jpqfVmNmhjBviFlQihjx0O0M3sfpGN.', 'admin'),
('Penulis Utama', 'author', 'author@blog.local', '$2y$10$DwOMjN5A7Q/uJB2z14.Ky.c0AtCMAzyCuRR4KYKYKG.h7S9e4xG9O', 'author'),
('User Demo', 'userdemo', 'user@blog.local', '$2y$10$ng.uJ3ZvLT74/ewRmX7BHOjsEqLZnIy9GShOZnmOZSlcFy6.Ulp0K', 'user');

INSERT INTO categories (name, slug) VALUES
('Teknologi', 'teknologi'),
('Gaya Hidup', 'gaya-hidup'),
('Travel', 'travel');

INSERT INTO tags (name, slug) VALUES
('PHP', 'php'),
('MySQL', 'mysql'),
('Produktivitas', 'produktivitas'),
('Tips', 'tips');

INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, image_url, is_published) VALUES
(1, 1, 'Membangun Blog Dinamis dengan PHP', 'membangun-blog-dinamis-dengan-php', 'Panduan praktis membuat blog dinamis menggunakan PHP dan MySQL.', 'Artikel ini membahas cara membangun blog dinamis secara bertahap. Mulai dari desain database, autentikasi role, hingga implementasi CRUD artikel yang aman dan mudah dikelola.', 'https://images.unsplash.com/photo-1518773553398-650c184e0bb3?auto=format&fit=crop&w=1200&q=80', 1),
(2, 2, 'Rutinitas Pagi yang Bikin Fokus', 'rutinitas-pagi-yang-bikin-fokus', 'Rutinitas sederhana untuk menjaga energi dan fokus harian.', 'Mulailah hari dengan 3 kebiasaan inti: hidrasi, prioritas tugas, dan sesi kerja fokus 45 menit. Konsistensi kecil membawa dampak besar dalam produktivitas.', 'https://images.unsplash.com/photo-1499750310107-5fef28a66643?auto=format&fit=crop&w=1200&q=80', 1),
(2, 3, 'Destinasi Weekend Hemat dan Seru', 'destinasi-weekend-hemat-dan-seru', 'Ide liburan singkat yang ramah budget dan tetap berkesan.', 'Tidak perlu jauh untuk liburan menyenangkan. Pilih lokasi dekat kota, susun itinerary ringan, dan fokus pada pengalaman, bukan pengeluaran besar.', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1200&q=80', 1);

INSERT INTO post_tags (post_id, tag_id) VALUES
(1,1),(1,2),(2,3),(2,4),(3,4);

INSERT INTO comments (post_id, name, email, message, is_approved) VALUES
(1, 'Rina', 'rina@mail.com', 'Artikelnya jelas dan sangat membantu. Terima kasih!', 1),
(2, 'Doni', 'doni@mail.com', 'Tips paginya sederhana tapi ngena.', 1);
