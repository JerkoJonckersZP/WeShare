-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 26 apr 2024 om 16:15
-- Serverversie: 10.4.28-MariaDB
-- PHP-versie: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `weshare`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `comment` varchar(240) NOT NULL,
  `commented_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `comments`
--

INSERT INTO `comments` (`id`, `user`, `post`, `comment`, `commented_at`) VALUES
(3, 8, 31, 'Test', '2024-04-15 07:37:37');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `user_one` int(11) NOT NULL,
  `user_two` int(11) NOT NULL,
  `request_accepted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `friends`
--

INSERT INTO `friends` (`id`, `user_one`, `user_two`, `request_accepted_at`) VALUES
(22, 8, 10, '2024-04-19 12:21:59'),
(26, 8, 12, '2024-04-26 13:48:06');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `friend_requests`
--

CREATE TABLE `friend_requests` (
  `id` int(11) NOT NULL,
  `requestor` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `liked_posts`
--

CREATE TABLE `liked_posts` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `post` int(11) NOT NULL,
  `liked_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `receiver` int(11) NOT NULL,
  `message` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `messages`
--

INSERT INTO `messages` (`id`, `sender`, `receiver`, `message`, `sent_at`) VALUES
(46, 8, 12, 'Hey Koni', '2024-04-25 11:12:51'),
(47, 12, 8, 'Hey Jerko', '2024-04-25 11:30:29'),
(48, 12, 8, 'Imad is a beta', '2024-04-25 11:31:18'),
(49, 8, 10, 'Snelheidsfunctie', '2024-04-26 07:47:23'),
(50, 8, 10, 'Johnny', '2024-04-26 12:48:53'),
(51, 8, 10, 'Ewa Johnny', '2024-04-26 12:53:07'),
(52, 8, 10, 'Joo Koni', '2024-04-26 12:59:00'),
(53, 8, 12, 'Yes he is!', '2024-04-26 12:59:05'),
(54, 8, 10, 'JORNE', '2024-04-26 13:00:30'),
(55, 8, 10, 'JORN', '2024-04-26 13:00:31'),
(56, 8, 10, 'AAA', '2024-04-26 13:00:33'),
(57, 8, 10, 'AJAKAEAKE', '2024-04-26 13:00:34'),
(58, 8, 10, 'EKA', '2024-04-26 13:00:34'),
(59, 8, 10, 'KEA', '2024-04-26 13:00:34'),
(60, 8, 10, 'EKA', '2024-04-26 13:00:34'),
(61, 8, 10, 'EKAKEA', '2024-04-26 13:00:35'),
(62, 8, 10, 'KEA', '2024-04-26 13:00:35'),
(63, 8, 10, 'EKA', '2024-04-26 13:00:35'),
(64, 8, 10, 'EKA', '2024-04-26 13:00:35'),
(65, 8, 10, 'EA', '2024-04-26 13:00:35'),
(66, 8, 10, 'EKA', '2024-04-26 13:00:36'),
(67, 8, 10, 'Johny', '2024-04-26 13:01:47'),
(68, 8, 10, 'Johnny toch', '2024-04-26 13:20:45'),
(69, 8, 10, 'Typisch gij he', '2024-04-26 13:20:49');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `message` varchar(240) NOT NULL,
  `photo` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `posts`
--

INSERT INTO `posts` (`id`, `user`, `message`, `photo`, `created_at`) VALUES
(6, 8, 'MANCHESTER CITY - KEVIN DE BRUYNE #GOAT', 'post_8_1705674239.png', '2024-01-19 14:23:59'),
(7, 8, 'FC BARCELONA - LIONEL MESSI #GOAT', 'post_8_1705674518.png', '2024-01-19 14:28:38'),
(9, 8, 'ROMELU LUKAKU', 'post_8_1705674972.png', '2024-01-19 14:36:12'),
(10, 8, 'THOMAS MULLER - BAYERN MUNCHEN #GOAT', 'post_8_1705914266.png', '2024-01-22 09:04:26'),
(11, 8, 'KYLIAN MBAPPE - PSG #BAD #LOSER #CANTPLAY', 'post_8_1705935481.png', '2024-01-22 14:58:01'),
(23, 8, 'NEYMAAAAR JR', 'post_8_1706608395.png', '2024-01-30 09:53:15'),
(24, 8, 'NEYMAR JUNIOORRRRR', 'post_8_1706609174.png', '2024-01-30 10:06:14'),
(26, 8, 'Dit is Mohamed Aoulad Abdelkader.', 'post_8_1709635848.png', '2024-03-05 10:50:48'),
(28, 8, 'Noem me historicus #Middeleeuws', 'post_8_1709903527.png', '2024-03-08 13:12:07'),
(31, 8, 'Vandaag een nieuw product verzonnen voor Exo Terra!', 'post_8_1713163622.png', '2024-04-15 06:47:02');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `email_address` text NOT NULL,
  `password` text NOT NULL,
  `description` varchar(160) NOT NULL,
  `profile_picture` text NOT NULL DEFAULT 'default.png',
  `user_type` int(11) NOT NULL DEFAULT 1,
  `private_account` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email_address`, `password`, `description`, `profile_picture`, `user_type`, `private_account`, `created_at`) VALUES
(8, 'Jerko Jonckers', 'jerko.jonckers@bazandpoort.be', '$2y$10$sORRgz9KITWXRrx.tUT8e.CKi4bsgNdX6so1kxHyAFNf38lvtKFkW', 'Founder of WeShare\r\n( 1,75m ) Mohamed: \"Wollah ik zweer ik ben 1,80m\"', 'profile_picture_8_1713794159.png', 1, 0, '2023-12-05 09:52:14'),
(10, 'Jorne Spileers', 'jorne.spileers@gmail.com', '$2y$10$1KGz/LEn/B7N.taA4n.qTeEKkpXaxgpdDiUXgKLqCBkPhjuuq9nhS', 'Club Brugge playah', 'profile_picture_10_1705565244.png', 1, 0, '2024-01-18 08:06:14'),
(12, 'Koni De Winter', 'koni.dewinter@gmail.com', '$2y$10$WzdEcazzK3nKhtzSQJf6/unm.JgK3sWQOMxHLRHKRjr7JE.3uRC9S', 'Player of Genoa CFC', 'profile_picture_12_1713528857.png', 1, 0, '2024-04-19 12:10:53');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_types`
--

CREATE TABLE `user_types` (
  `id` int(11) NOT NULL,
  `user_type` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user_types`
--

INSERT INTO `user_types` (`id`, `user_type`) VALUES
(1, 'user'),
(2, 'moderator'),
(3, 'administrator');

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `friends`
--
ALTER TABLE `friends`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `friend_requests`
--
ALTER TABLE `friend_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `liked_posts`
--
ALTER TABLE `liked_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user_types`
--
ALTER TABLE `user_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT voor een tabel `friends`
--
ALTER TABLE `friends`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT voor een tabel `friend_requests`
--
ALTER TABLE `friend_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT voor een tabel `liked_posts`
--
ALTER TABLE `liked_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=70;

--
-- AUTO_INCREMENT voor een tabel `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT voor een tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
