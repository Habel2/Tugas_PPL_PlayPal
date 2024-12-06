-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 06 Des 2024 pada 16.50
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `playpal_ppl`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `comment_text` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `like` int(11) DEFAULT 0,
  `dislike` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `comments`
--

INSERT INTO `comments` (`comment_id`, `post_id`, `user_id`, `comment_text`, `created_at`, `like`, `dislike`) VALUES
(1, 11, 3, '<p>waduh rek</p>', '2024-11-28 01:51:06', 0, 0),
(2, 11, 1, '<p>inimah idamannya si imut rek</p>', '2024-11-28 04:44:55', 0, 0),
(3, 11, 1, '<p>awokaowkoawkaowkw</p>', '2024-11-28 05:16:10', 0, 0),
(4, 11, 1, '<p>aowkaowkoakww <img src=\"img/comments/comment_1733364779_67510c2ba0bfc.jpeg\"></p>', '2024-12-05 02:12:59', 0, 0),
(5, 13, 3, '<p>aowkaowkoakwoq</p>', '2024-12-05 04:37:27', 0, 0),
(12, 41, 3, '<p>UWOGHH RANNI!!!!</p>', '2024-12-06 12:41:17', 0, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `games`
--

CREATE TABLE `games` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `games`
--

INSERT INTO `games` (`game_id`, `game_name`) VALUES
(5, 'DayZ'),
(4, 'Elden Ring'),
(3, 'Genshin Impact'),
(1, 'Mobile Legends'),
(2, 'Valorant');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes_dislikes`
--

CREATE TABLE `likes_dislikes` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('like','dislike') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likes_dislikes`
--

INSERT INTO `likes_dislikes` (`id`, `post_id`, `user_id`, `type`, `created_at`) VALUES
(34, 11, 1, 'like', '2024-12-06 06:19:21'),
(35, 13, 1, 'like', '2024-12-06 06:19:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `likes_dislikes_comments`
--

CREATE TABLE `likes_dislikes_comments` (
  `id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` enum('like','dislike') NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `likes_dislikes_comments`
--

INSERT INTO `likes_dislikes_comments` (`id`, `comment_id`, `user_id`, `type`, `created_at`) VALUES
(44, 12, 1, 'like', '2024-12-06 19:47:16');

-- --------------------------------------------------------

--
-- Struktur dari tabel `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `text_content` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `likes` int(11) DEFAULT 0,
  `dislikes` int(11) DEFAULT 0,
  `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `posts`
--

INSERT INTO `posts` (`post_id`, `user_id`, `game_id`, `text_content`, `created_at`, `likes`, `dislikes`, `title`) VALUES
(11, 1, 3, '<p>Mas Rusdi Cuy<img src=\"img/3_1732161527_673eaff77dc5f.jpeg\"> aowkoawkaowk</p>', '2024-11-21 03:58:47', 1, 0, 'UWOGHHHHHHHHHH'),
(13, 3, 1, '<p>aowkoawkoawkodqk</p><p> <img src=\"img/1_1733373404_67512ddc09c4b.jpeg\"></p>', '2024-12-05 04:36:44', 1, 0, 'Lorem Ipsum'),
(15, 3, 3, '<p>Skibidi skibidi hawktuah hawk</p>', '2024-12-06 09:35:14', 0, 0, 'Skibidi'),
(17, 3, 3, '<p>awdasdawfas</p>', '2024-12-06 09:58:14', 0, 0, 'Lorem Ipsum'),
(41, 3, 3, '<p>disini saya akan mengupload image berukuran besar</p><p><img src=\"img/6752ecc059674.png\"></p>', '2024-12-06 12:23:29', 0, 0, 'tes upload image besar'),
(43, 3, 3, '<p>Disini saya hanya mengupload teks saja</p>', '2024-12-06 12:57:34', 0, 0, 'tes upload teks'),
(45, 3, 4, '<p>ini Ini eldenring coy<img src=\"img/6752f8593f58a.png\"></p>', '2024-12-06 13:13:14', 0, 0, 'Lorem Ipsum'),
(46, 3, 4, '<p><img src=\"img/6752f8e881609.png\"></p>', '2024-12-06 13:15:27', 0, 0, 'Posting apa ya'),
(47, 3, 5, '<p>Disini saya terkena penyakit, kedinginan dan kekurangan makanan. Jadi saya memilih untuk mengakhiri hidup saya dengan tombak<img src=\"img/6752fb54e990c.png\"></p>', '2024-12-06 13:26:44', 0, 0, 'Ini DayZ'),
(48, 3, 5, '', '2024-12-06 13:27:37', 0, 0, 'DayZ susahhh');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('user','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'habel', 'habelfebriansitanggang@gmail.com', '$2y$10$e8gY.RvWrczqctrOpfHJQ.jEXX.54eizGMTySi6LCHauMgT60xB7y', 'admin', '2024-11-13 19:14:05'),
(3, 'admin', 'admin@gmail.com', '$2y$10$JTMi3Olxjd6iPyKJPX/aWesJmhUcluCvr5pg5gK6T31Yr7dOrhGt6', 'user', '2024-11-13 19:46:00'),
(4, '', '', '$2y$10$/q163T4Hw/Ouh9vph.ByUOBnZEpvmvw1iYquIO1TN4tlc0v9z04LG', 'user', '2024-12-04 19:28:34'),
(5, 'user', 'user@gmail.com', '$2y$10$vYaSPW/VoWZryU87yrDQCeSyMENvueAG3c7Q8ZccPQZdCRqCBpKxO', 'user', '2024-12-05 04:32:10');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`game_id`),
  ADD UNIQUE KEY `game_name` (`game_name`);

--
-- Indeks untuk tabel `likes_dislikes`
--
ALTER TABLE `likes_dislikes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_id` (`post_id`,`user_id`,`type`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `likes_dislikes_comments`
--
ALTER TABLE `likes_dislikes_comments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `comment_id` (`comment_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indeks untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `games`
--
ALTER TABLE `games`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `likes_dislikes`
--
ALTER TABLE `likes_dislikes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `likes_dislikes_comments`
--
ALTER TABLE `likes_dislikes_comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT untuk tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `likes_dislikes`
--
ALTER TABLE `likes_dislikes`
  ADD CONSTRAINT `likes_dislikes_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`post_id`),
  ADD CONSTRAINT `likes_dislikes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ketidakleluasaan untuk tabel `likes_dislikes_comments`
--
ALTER TABLE `likes_dislikes_comments`
  ADD CONSTRAINT `likes_dislikes_comments_ibfk_1` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`comment_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likes_dislikes_comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `games` (`game_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
