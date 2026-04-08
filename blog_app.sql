-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Apr 2026 pada 02.57
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog_app`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `created_at`) VALUES
(4, 'Artist', 'Artist', '2026-04-07 04:26:55'),
(5, 'Award', 'award', '2026-04-07 14:47:47'),
(6, 'Actor', 'actor', '2026-04-07 14:48:34'),
(7, 'Idol', 'idol', '2026-04-07 14:48:38'),
(8, 'Seventeen', 'seventeen', '2026-04-07 14:48:43');

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `is_approved` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `title` varchar(180) NOT NULL,
  `slug` varchar(200) NOT NULL,
  `excerpt` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `category_id`, `title`, `slug`, `excerpt`, `content`, `image_url`, `is_published`, `created_at`, `updated_at`) VALUES
(4, 1, 4, 'Perpanjangan Kontrak Kim Mingyu: Loyalitas dan Masa Depan Bersama SEVENTEEN', 'perpanjangan-kontrak-kim-mingyu-loyalitas-dan-masa-depan-bersama-seventeen', 'Kim Mingyu bersama member SEVENTEEN memutuskan untuk memperpanjang kontrak dengan agensi mereka, Pledis Entertainment.', 'Kabar bahagia datang bagi para penggemar K-pop, khususnya CARAT. Kim Mingyu bersama seluruh anggota SEVENTEEN resmi memperpanjang kontrak mereka dengan Pledis Entertainment.\r\n\r\nKeputusan ini menjadi langkah besar yang tidak hanya mencerminkan kepercayaan para member terhadap agensi, tetapi juga menunjukkan hubungan profesional yang solid antara artis dan manajemen. Dalam industri K-pop yang dikenal memiliki kontrak ketat dan masa aktif grup yang terbatas, perpanjangan ini menjadi sinyal positif bahwa SEVENTEEN masih memiliki perjalanan panjang ke depan.\r\n\r\nBagi Kim Mingyu sendiri, keputusan ini memperlihatkan dedikasinya terhadap grup yang telah membesarkan namanya. Sebagai salah satu member yang memiliki popularitas tinggi, baik di bidang musik maupun variety show, Mingyu tetap memilih untuk melanjutkan perjalanan bersama timnya daripada mengambil jalur solo sepenuhnya.\r\n\r\nPara penggemar pun menyambut berita ini dengan penuh antusias. Banyak yang merasa lega karena kekhawatiran mengenai kemungkinan bubarnya grup akhirnya terjawab. Media sosial dipenuhi dengan dukungan dan ucapan bahagia dari CARAT di seluruh dunia.\r\n\r\nSelain itu, perpanjangan kontrak ini juga membuka peluang baru bagi SEVENTEEN untuk terus berkembang secara global. Dengan popularitas yang terus meningkat, mereka diprediksi akan semakin aktif dalam proyek internasional, tur dunia, hingga kolaborasi dengan artis global lainnya.\r\n\r\nKe depan, Kim Mingyu dan member lainnya diharapkan dapat terus menghadirkan karya-karya berkualitas yang mampu mempertahankan posisi mereka sebagai salah satu grup K-pop terdepan. \r\nPerpanjangan kontrak ini bukan hanya sekadar formalitas, tetapi juga simbol dari komitmen, kerja sama, dan perjalanan panjang yang masih akan terus berlanjut antara artis dan penggemarnya.', '/blog/uploads/post-20260407063654-e7958b45.jpg', 1, '2026-04-07 04:31:01', '2026-04-07 04:36:54'),
(5, 2, 7, 'Comeback War Bulan Ini: Dompet Aman atau Nangis di Pojokan?', 'comeback-war-bulan-ini-dompet-aman-atau-nangis-di-pojokan', 'Jadwal comeback bulan ini bener-bener brutal, persaingan chart makin panas. Siapkan mental dan saldo ya, guys!', 'Gila sih, bulan ini bener-bener definisi \"war\" yang sesungguhnya di industri musik! Dari grup senior yang tiba-tiba muncul buat \"kasih paham\" siapa bosnya, sampai rookie yang lagunya langsung easy listening dan nangkring di Top 10 MelOn, semuanya rebutan takhta. Lo wajib banget pantau siapa yang bakal bawa pulang First Win di Music Bank minggu ini karena skornya tipis-tipis banget. Jangan lupa juga cek konsep fotonya; ada yang pake konsep dark-royalty yang bikin pusing tujuh keliling karena visualnya terlalu mahal, ada juga yang fresh-summer ala anak pantai yang bikin adem.\r\n\r\nBuat lo yang pengen ikut pre-order (PO) album, mending cek dulu benefit dari tiap toko (Ktown4u, Withmuu, dkk) biar dapet photocard (PC) idaman yang prio banget buat dikoleksi atau dijadiin bahan trade. Jangan sampai nyesel belakangan karena telat PO terus harga PC-nya jadi selangit di tangan para reseller. Pokoknya, bulan ini adalah ujian berat buat iman dan tabungan kita semua. Apalagi kalau grup favorit lo rilis versi digipack atau platform yang PC-nya lucu-lucu banget. Stay tuned dan jangan lupa streaming terus biar idola kita menang trofi!', '/blog/uploads/post-20260407165302-6e641688.jfif', 1, '2026-04-07 14:53:02', '2026-04-07 14:53:02'),
(6, 2, 7, 'Hearts2Hearts: Girlgroup Baru SM yang Lagi Viral Banget!', 'hearts2hearts-girlgroup-baru-sm-yang-lagi-viral-banget', 'Kenalan sama Hearts2Hearts (H2H), \"adik\" aespa yang baru aja sukses gelar fanmeeting pertama mereka tahun ini', 'Kalau lo denger nama \"Heart Two Heart\", mungkin maksud lo adalah Hearts2Hearts! Girlgroup terbaru SM Entertainment ini bener-bener lagi jadi buah bibir. Setelah debut yang sukses banget di 2025, tahun 2026 ini mereka makin gila aktivitasnya. Di bulan Februari kemarin, 8 member H2H (Carmen, Jiwoo, Yuha, Stella, Juun, A-na, Ian, dan Ye-on) baru aja nyelesaiin Fanmeeting pertama mereka \"HEARTS 2 HOUSE\" di Olympic Hall. Musik mereka yang fresh dan konsep visual yang unreal bikin banyak orang langsung jatuh cinta. SM emang gak pernah gagal kalau soal bikin girlgroup ikonik. Buat lo yang lagi nyari \"anak asuh\" baru buat didukung, Hearts2Hearts bener-bener kandidat paling kuat buat jadi Next Global Queen!', '/blog/uploads/post-20260407165801-b7904fbb.jfif', 1, '2026-04-07 14:58:01', '2026-04-07 14:58:01'),
(7, 2, 4, 'Go Youn-jung & Koo Kyo-hwan: Drama Baru \"We Are All Trying Here\" Segera Tayang!', 'go-youn-jung-koo-kyo-hwan-drama-baru-we-are-all-trying-here-segera-tayang', 'Si cantik Go Youn-jung balik lagi ke layar kaca lewat drama JTBC bareng Koo Kyo-hwan. Siapkan diri buat dapet asupan akting berkelas!', 'Aktris paling hits saat ini, Go Youn-jung, siap bikin kita baper lagi lewat drama terbarunya \"We Are All Trying Here\" yang bakal tayang di JTBC mulai 18 April 2026. Di sini, dia bakal adu akting sama aktor karismatik Koo Kyo-hwan. Ceritanya bakal dalem banget, tentang orang-orang yang berjuang nyari kedamaian di tengah rasa iri dan trauma. Go Youn-jung bakal memerankan seorang produser yang punya luka masa lalu. Kabarnya, penulis naskahnya sama dengan \"My Liberation Notes\", jadi lo bisa bayangin betapa puitis dan relateable dialog-dialognya nanti. Selain visualnya yang selalu bikin silau, kemampuan akting Go Youn-jung di sini bener-bener diuji buat mainin emosi yang lebih kompleks. Wajib masuk watchlist bulan ini!', '/blog/uploads/post-20260407165921-1fd5a74f.jfif', 1, '2026-04-07 14:59:21', '2026-04-07 14:59:21'),
(8, 2, 7, 'Irene Red Velvet \"Biggest Fan\": Debut Solo Full Album yang Slay Parah!', 'irene-red-velvet-biggest-fan-debut-solo-full-album-yang-slay-parah', 'Leader Red Velvet akhirnya rilis full album solo perdana bertajuk \"Biggest Fan\". Visual dan vokalnya bener-bener top tier!', 'Setelah penantian panjang, Irene Red Velvet akhirnya resmi rilis 1st Full Album bertajuk \"Biggest Fan\" di akhir Maret 2026 kemarin. Album ini isinya 10 lagu yang nunjukin sisi Irene yang lebih dewasa, confident, dan tentu aja estetik parah. Teasernya aja udah bikin heboh karena pake konsep iklan fiksi yang retro banget. Di album ini, Irene mau bilang kalau dia adalah pendukung terbesar buat dirinya sendiri dan para fansnya (ReLuv). Penjualan album fisiknya pun langsung tembus ratusan ribu kopi dalam sekejap. Ini bukti kalau kharisma Irene emang gak ada lawan. Jangan lupa dengerin track utamanya, musiknya bener-bener candu dan cocok banget buat nemenin hari-hari lo yang lagi butuh suntikan semangat!', '/blog/uploads/post-20260407170101-7f98d4cd.jfif', 1, '2026-04-07 15:01:01', '2026-04-07 15:01:01'),
(9, 2, 7, 'Karina aespa & Aktor Lee Jae-wook: Kabar Putus yang Bikin Fandom Geger!', 'karina-aespa-aktor-lee-jae-wook-kabar-putus-yang-bikin-fandom-geger', 'Setelah sempat \"go public\" dan bikin heboh dunia K-pop, hubungan visual legendaris ini dikabarkan kandas karena tekanan publik', 'Dunia hiburan Korea baru aja berduka (atau mungkin ada yang senang?). Hubungan Karina aespa dan aktor Lee Jae-wook yang sempat dikonfirmasi oleh Dispatch awal tahun lalu dikabarkan resmi berakhir. Skandal asmara mereka bener-bener jadi ujian berat, terutama buat Karina yang sampai harus nulis surat permintaan maaf buat MY (fans aespa) karena dianggap \"mengecewakan\" grup yang lagi di puncak. Netizen berspekulasi kalau jadwal yang super padat dan komentar jahat dari para haters jadi penyebab utama mereka milih buat balik jadi rekan kerja aja. Banyak yang menyayangkan, karena secara visual mereka bener-bener power couple banget. Di forum komunitas, sekarang lagi rame perdebatan: apakah idol emang gak boleh punya kehidupan asmara pribadi demi karier grup?', '/blog/uploads/post-20260407170422-f12ea4c8.jfif', 1, '2026-04-07 15:04:22', '2026-04-07 15:04:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `post_tags`
--

CREATE TABLE `post_tags` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `post_tags`
--

INSERT INTO `post_tags` (`post_id`, `tag_id`) VALUES
(5, 7),
(6, 7),
(7, 7),
(8, 6),
(9, 8);

-- --------------------------------------------------------

--
-- Struktur dari tabel `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(80) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `tags`
--

INSERT INTO `tags` (`id`, `name`, `slug`, `created_at`) VALUES
(5, 'Artist', 'Artist', '2026-04-07 04:28:01'),
(6, 'Daily', 'daily', '2026-04-07 14:32:00'),
(7, 'Work', 'work', '2026-04-07 14:32:04'),
(8, 'Realitionship', 'realitionship', '2026-04-07 14:32:09');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','author') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `full_name`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Administrator Blog', 'admin', 'admin@blog.local', '$2y$10$e1vMoB73slWjQ57fdHDrI.jpqfVmNmhjBviFlQihjx0O0M3sfpGN.', 'admin', '2026-04-07 04:22:59'),
(2, 'Mingyuewife', 'wifey', 'author@blog.local', '$2y$10$nLmUjAFqOLHsYEItd/qOo.ocB.6UkHtXOXgrlNYyLyMSoYbWb1rHS', 'author', '2026-04-07 04:22:59');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indeks untuk tabel `post_tags`
--
ALTER TABLE `post_tags`
  ADD PRIMARY KEY (`post_id`,`tag_id`),
  ADD KEY `tag_id` (`tag_id`);

--
-- Indeks untuk tabel `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT untuk tabel `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ketidakleluasaan untuk tabel `post_tags`
--
ALTER TABLE `post_tags`
  ADD CONSTRAINT `post_tags_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `post_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
