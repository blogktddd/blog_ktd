-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 08, 2022 lúc 03:27 AM
-- Phiên bản máy phục vụ: 10.4.24-MariaDB
-- Phiên bản PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ktd_blog`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment`
--

CREATE TABLE `comment` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `comment_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `upload_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comment`
--

INSERT INTO `comment` (`id`, `user_id`, `post_id`, `comment_content`, `upload_time`) VALUES
(1, 2, 1, '123', '2022-06-29 15:03:32'),
(2, 2, 2, '234', '2022-06-29 15:42:18'),
(3, 2, 3, '123', '2022-06-20 16:26:03'),
(4, 6, 8, '123', '2022-06-21 09:41:55'),
(5, 6, 8, '123', '2022-06-21 09:41:57'),
(6, 7, 9, '1', '2022-07-05 19:47:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220620094256', '2022-06-20 11:43:04', 71),
('DoctrineMigrations\\Version20220620094409', '2022-06-20 11:44:14', 80),
('DoctrineMigrations\\Version20220620094455', '2022-06-20 11:44:58', 87),
('DoctrineMigrations\\Version20220620094850', '2022-06-20 11:48:53', 86),
('DoctrineMigrations\\Version20220620095025', '2022-06-20 11:50:30', 28),
('DoctrineMigrations\\Version20220620131402', '2022-06-20 20:14:12', 129),
('DoctrineMigrations\\Version20220620132323', '2022-06-20 20:23:30', 38),
('DoctrineMigrations\\Version20220620134647', '2022-06-20 20:46:54', 137),
('DoctrineMigrations\\Version20220621010449', '2022-06-21 08:04:59', 41),
('DoctrineMigrations\\Version20220621010528', '2022-06-21 08:05:39', 15),
('DoctrineMigrations\\Version20220628004441', '2022-06-28 02:44:47', 475);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seen` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `notification`
--

INSERT INTO `notification` (`id`, `sender_id`, `receiver_id`, `type`, `seen`) VALUES
(2, 2, 2, 'like', 'yes'),
(3, 2, 2, 'comment', 'yes'),
(4, 2, 3, 'invite', 'no'),
(5, 2, 2, 'like', 'yes'),
(6, 3, 2, 'like', 'yes'),
(7, 2, 2, 'comment', 'yes'),
(8, 2, 3, 'like', 'yes'),
(9, 2, 3, 'like', 'yes'),
(10, 2, 3, 'comment', 'yes'),
(11, 3, 3, 'like', 'yes'),
(12, 3, 3, 'like', 'yes'),
(13, 2, 6, 'invite', 'no'),
(14, 2, 6, 'invite', 'no'),
(15, 2, 6, 'invite', 'no'),
(16, 2, 6, 'invite', 'no'),
(17, 6, 2, 'like', 'yes'),
(18, 6, 2, 'comment', 'yes'),
(19, 6, 2, 'comment', 'yes'),
(20, 6, 2, 'invite', 'no'),
(21, 6, 7, 'invite', 'no'),
(22, 7, 7, 'like', 'yes'),
(23, 7, 7, 'comment', 'yes');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post`
--

CREATE TABLE `post` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `caption` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_like` int(11) DEFAULT NULL,
  `total_comment` int(11) DEFAULT NULL,
  `upload_time` datetime NOT NULL,
  `deleted` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post`
--

INSERT INTO `post` (`id`, `user_id`, `caption`, `image`, `total_like`, `total_comment`, `upload_time`, `deleted`) VALUES
(1, 2, 'test1', '62bc0729e3c11Nitro_Wallpaper_5000x2813.jpg', 1, 1, '2022-06-29 15:02:49', 'true'),
(2, 2, '123', NULL, 2, 1, '2022-06-17 15:36:59', 'false'),
(3, 3, '123', NULL, 2, 1, '2022-06-29 16:02:19', 'false'),
(4, 3, '1234', NULL, 2, NULL, '2022-06-20 16:03:10', 'false'),
(5, 2, '', '62b11b55a29a7Nitro_Wallpaper_5000x2813.jpg', NULL, NULL, '2022-06-21 08:13:57', 'true'),
(6, 2, '', '62b11b5889beeNitro_Wallpaper_5000x2813.jpg', NULL, NULL, '2022-06-21 08:14:00', 'true'),
(7, 3, '', '62b11bcd546e9planet9_Wallpaper_5000x2813.jpg', NULL, NULL, '2022-06-21 08:15:57', 'false'),
(8, 2, '1234', NULL, 1, 2, '2022-06-21 08:31:39', 'false'),
(9, 7, '12345', '62c432f042e13Nitro_Wallpaper_5000x2813.jpg', 1, 1, '2022-07-05 19:46:54', 'true');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reaction`
--

CREATE TABLE `reaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reaction`
--

INSERT INTO `reaction` (`id`, `user_id`, `post_id`) VALUES
(2, 2, 1),
(3, 2, 2),
(4, 3, 2),
(5, 2, 4),
(6, 2, 3),
(7, 3, 4),
(8, 3, 3),
(9, 6, 8),
(10, 7, 9);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `relationship`
--

CREATE TABLE `relationship` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `friend_id` int(11) NOT NULL,
  `status` varchar(1) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `relationship`
--

INSERT INTO `relationship` (`id`, `user_id`, `friend_id`, `status`) VALUES
(1, 2, 3, '1'),
(2, 3, 2, '1'),
(9, 6, 2, '1'),
(10, 2, 6, '1');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(6) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(24) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `new_password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user`
--

INSERT INTO `user` (`id`, `username`, `roles`, `password`, `gender`, `fullname`, `avatar`, `login_status`, `email`, `phone`, `address`, `new_password`) VALUES
(2, 'duy123', '[\"ROLE_USER\"]', '$2y$13$KwsPR47PW15ycIg5Ckh.G.7Ffq53GzyIwxC41zllpUDSqN0fJKNAu', 'male', 'duy', '62b11b5889beeNitro_Wallpaper_5000x2813.jpg', 'online', '123@gmail.com', '123', '123', NULL),
(3, 'admin', '[\"ROLE_ADMIN\"]', '$2y$13$PPhqCslQ/yz7caJVdmhrweywkIr6TC4j37opSK.qyv53rsE8RDL7a', 'male', 'admin', '62b11bcd546e9planet9_Wallpaper_5000x2813.jpg', 'online', NULL, NULL, NULL, NULL),
(4, '123', '[\"ROLE_USER\"]', '$2y$13$ltN9.OctxmAJLw1sixH00u2/a.mN4/xpuF.oEqm4apCimJXUJW.Bu', 'male', '12345', 'avatar.png', 'offline', NULL, NULL, NULL, NULL),
(5, 'khanh', '[\"ROLE_USER\"]', '$2y$13$QAN0E5w0dtnuHeuvssG/kOjnA6tWv7it0O3H1K/RwAX6o3O6yK.qu', 'male', 'Khanh', 'avatar.png', 'offline', NULL, NULL, NULL, NULL),
(6, 'user', '[\"ROLE_USER\"]', '$2y$13$tN69L9cJbBzxYTn0ubOVBejrR0jl7Cb05jP8IpwD04aIqfbajL1vq', 'male', 'user', 'avatar.png', 'online', NULL, NULL, NULL, NULL),
(7, 'tester', '[\"ROLE_USER\"]', '$2y$13$jaGxIOewRhNmPcf5f12f3.F62QrGHjEnxXRxowa07kzW1CUekSc4y', 'male', 'tester', 'avatar.png', 'online', NULL, NULL, NULL, NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_9474526CA76ED395` (`user_id`),
  ADD KEY `IDX_9474526C4B89032C` (`post_id`);

--
-- Chỉ mục cho bảng `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Chỉ mục cho bảng `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Chỉ mục cho bảng `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BF5476CAF624B39D` (`sender_id`),
  ADD KEY `IDX_BF5476CACD53EDB6` (`receiver_id`);

--
-- Chỉ mục cho bảng `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_5A8A6C8DA76ED395` (`user_id`);

--
-- Chỉ mục cho bảng `reaction`
--
ALTER TABLE `reaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A4D707F7A76ED395` (`user_id`),
  ADD KEY `IDX_A4D707F74B89032C` (`post_id`);

--
-- Chỉ mục cho bảng `relationship`
--
ALTER TABLE `relationship`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_200444A0A76ED395` (`user_id`),
  ADD KEY `IDX_200444A06A5458E8` (`friend_id`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `comment`
--
ALTER TABLE `comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `post`
--
ALTER TABLE `post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `reaction`
--
ALTER TABLE `reaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `relationship`
--
ALTER TABLE `relationship`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `FK_9474526C4B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `FK_9474526CA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `FK_BF5476CACD53EDB6` FOREIGN KEY (`receiver_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_BF5476CAF624B39D` FOREIGN KEY (`sender_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `FK_5A8A6C8DA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `reaction`
--
ALTER TABLE `reaction`
  ADD CONSTRAINT `FK_A4D707F74B89032C` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`),
  ADD CONSTRAINT `FK_A4D707F7A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Các ràng buộc cho bảng `relationship`
--
ALTER TABLE `relationship`
  ADD CONSTRAINT `FK_200444A06A5458E8` FOREIGN KEY (`friend_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_200444A0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
