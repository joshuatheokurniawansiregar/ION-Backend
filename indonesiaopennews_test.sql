-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Des 2022 pada 16.41
-- Versi server: 10.4.21-MariaDB
-- Versi PHP: 8.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `indonesiaopennews_test`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_approval`
--

CREATE TABLE `admin_approval` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `author_description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `photo_profile_link` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profile_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profile_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `join_at` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_news_approval`
--

CREATE TABLE `admin_news_approval` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_content` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_slug` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_link` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `history`
--

CREATE TABLE `history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` datetime NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_09_01_072341_create_topics_table', 1),
(6, '2022_09_01_072342_create_subtopics_table', 1),
(7, '2022_09_01_072347_create_news_table', 1),
(8, '2022_12_12_072635_admin_approval', 1),
(9, '2022_12_14_025009_creaete_history_table', 1),
(10, '2022_12_14_034028_create_admin_news_approval_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `news`
--

CREATE TABLE `news` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `news_title` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_slug` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_link` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `news_picture_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `added_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) DEFAULT NULL,
  `news_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_topic_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `news`
--

INSERT INTO `news` (`id`, `news_title`, `news_content`, `news_slug`, `news_picture_link`, `news_picture_name`, `news_picture_path`, `added_at`, `updated_at`, `news_status`, `sub_topic_id`, `user_id`) VALUES
(4, 'Ayo', 'testsss1m', 'ayo', 'http://127.0.0.1:8000/storage/news_image/02.JPG', '02.JPG', 'storage/news_image', 1671976778008, NULL, 'Paid', 2, 2),
(5, 'Ayo', 'testsss1m', 'ayo', 'http://127.0.0.1:8000/storage/news_image/02_1.JPG', '02_1.JPG', 'storage/news_image', 1671980850170, NULL, 'Paid', 2, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_topics`
--

CREATE TABLE `sub_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sub_topic_title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_topic_slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL,
  `topic_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sub_topics`
--

INSERT INTO `sub_topics` (`id`, `sub_topic_title`, `sub_topic_slug`, `added_at`, `updated_at`, `topic_id`) VALUES
(2, 'Test Test', 'test-test', 1671537941739, 0, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `topics`
--

CREATE TABLE `topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_title` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `topic_slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `added_at` bigint(20) UNSIGNED NOT NULL,
  `updated_at` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `topics`
--

INSERT INTO `topics` (`id`, `topic_title`, `topic_slug`, `added_at`, `updated_at`) VALUES
(1, 'Aye', 'aye', 1671537866547, 0),
(2, 'Test Topic', 'test-topic', 1671877263791, 0),
(3, 'Test Topic 1', 'test-topic-1', 1671877731948, 0),
(4, 'Test Topic 2', 'test-topic-2', 1671877734856, 0),
(5, 'Test Topic 3', 'test-topic-3', 1671877737879, 0),
(6, 'Test Topic 4', 'test-topic-4', 1671877741733, 0),
(7, 'Test Topic 5', 'test-topic-5', 1671877746501, 0),
(8, 'Test Topic 6', 'test-topic-6', 1671877750489, 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_account_numer` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance` double UNSIGNED DEFAULT NULL,
  `photo_profile_link` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profile_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_profile_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `author_description`, `role`, `balance_account_numer`, `balance`, `photo_profile_link`, `photo_profile_name`, `photo_profile_path`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(2, 'Joshua Theo Kurniawan Siregar', 'joshua@gmail.com', 'password', 'Hi my name is Joshua Theo Kurniawan. I have hobby namely playing game and singing.', 'author', NULL, NULL, 'http://127.0.0.1:8000/storage/photo_profile/01_1.JPG', '01_1.JPG', 'storage/photo_profile', NULL, NULL, '2022-12-23 06:19:32', '2022-12-25 07:10:37'),
(3, 'test', 'joshuatest@gmail.com', 'joshuatheokur', 'Hi my name is Joshua Theo Kurniawan. I have hobby namely playing game and singing.', 'author', NULL, NULL, 'http://127.0.0.1:8000/storage/photo_profile/adminfix.jpg', 'adminfix.jpg', 'storage/photo_profile', NULL, NULL, '2022-12-25 08:30:31', '2022-12-25 08:32:22'),
(4, 'Test User', 'test@example.com', 'adminssss', NULL, 'admin', NULL, NULL, NULL, NULL, NULL, '2022-12-25 20:55:36', 'cq5Kapwwuf', '2022-12-25 20:55:42', '2022-12-25 20:55:42');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin_approval`
--
ALTER TABLE `admin_approval`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_approval_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `admin_news_approval`
--
ALTER TABLE `admin_news_approval`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admin_news_approval_news_title_unique` (`news_title`),
  ADD KEY `admin_news_approval_sub_topic_id_foreign` (`sub_topic_id`),
  ADD KEY `admin_news_approval_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `history`
--
ALTER TABLE `history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `history_user_id_foreign` (`user_id`),
  ADD KEY `history_news_id_foreign` (`news_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`),
  ADD KEY `news_sub_topic_id_foreign` (`sub_topic_id`),
  ADD KEY `news_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `sub_topics`
--
ALTER TABLE `sub_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sub_topics_sub_topic_title_unique` (`sub_topic_title`),
  ADD KEY `sub_topics_topic_id_foreign` (`topic_id`);

--
-- Indeks untuk tabel `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `topics_topic_title_unique` (`topic_title`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_balance_account_numer_unique` (`balance_account_numer`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_approval`
--
ALTER TABLE `admin_approval`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `admin_news_approval`
--
ALTER TABLE `admin_news_approval`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `history`
--
ALTER TABLE `history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sub_topics`
--
ALTER TABLE `sub_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `topics`
--
ALTER TABLE `topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin_approval`
--
ALTER TABLE `admin_approval`
  ADD CONSTRAINT `admin_approval_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `admin_news_approval`
--
ALTER TABLE `admin_news_approval`
  ADD CONSTRAINT `admin_news_approval_sub_topic_id_foreign` FOREIGN KEY (`sub_topic_id`) REFERENCES `sub_topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `admin_news_approval_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `history`
--
ALTER TABLE `history`
  ADD CONSTRAINT `history_news_id_foreign` FOREIGN KEY (`news_id`) REFERENCES `news` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_sub_topic_id_foreign` FOREIGN KEY (`sub_topic_id`) REFERENCES `sub_topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `news_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sub_topics`
--
ALTER TABLE `sub_topics`
  ADD CONSTRAINT `sub_topics_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
