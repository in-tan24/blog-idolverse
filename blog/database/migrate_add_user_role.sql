-- Jalankan jika database sudah terlanjur dibuat sebelum role user ditambahkan
USE blog_app;

ALTER TABLE users MODIFY role ENUM('admin', 'author', 'user') NOT NULL;

INSERT INTO users (full_name, username, email, password, role)
SELECT 'User Demo', 'userdemo', 'user@blog.local', '$2y$10$ng.uJ3ZvLT74/ewRmX7BHOjsEqLZnIy9GShOZnmOZSlcFy6.Ulp0K', 'user'
WHERE NOT EXISTS (SELECT 1 FROM users WHERE username = 'userdemo' OR email = 'user@blog.local');