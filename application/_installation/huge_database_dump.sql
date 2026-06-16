-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 16. Jun 2026 um 08:15
-- Server-Version: 10.4.32-MariaDB
-- PHP-Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `huge`
--

DELIMITER $$
--
-- Prozeduren
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AddChatParticipant` (IN `p_chat_id` INT, IN `p_user_id` INT)   BEGIN
    INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user_id);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CreateGroupChat` (IN `p_name` VARCHAR(255), OUT `p_chat_id` INT)   BEGIN
    INSERT INTO chats (name, is_group) VALUES (p_name, 1);
    SET p_chat_id = LAST_INSERT_ID();
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetChatByUserId` (IN `p_user_id` INT)   BEGIN
    SELECT
        c.id, c.name, c.is_group,
        (SELECT COUNT(*)
         FROM messages m
         JOIN chat_participants cp2 ON cp2.chat_id = m.chat_id AND cp2.user_id = p_user_id
         WHERE m.chat_id = c.id
           AND m.sender_id != p_user_id
           AND (cp2.last_read_at IS NULL OR m.created_at > cp2.last_read_at)
        ) AS unread_count,
        IF(c.is_group = 0,
            (SELECT cp3.user_id FROM chat_participants cp3
             WHERE cp3.chat_id = c.id AND cp3.user_id != p_user_id LIMIT 1),
            NULL
        ) AS partner_id
    FROM chats c
    JOIN chat_participants cp ON cp.chat_id = c.id
    WHERE cp.user_id = p_user_id
    ORDER BY c.id DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetChatInfo` (IN `p_chat_id` INT)   BEGIN
    SELECT id, name, is_group FROM chats WHERE id = p_chat_id LIMIT 1;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetMessagesByChatId` (IN `p_chat_id` INT)   BEGIN
    SELECT m.id, m.sender_id, m.content, m.created_at, u.user_name
    FROM messages m
    JOIN users u ON u.user_id = m.sender_id
    WHERE m.chat_id = p_chat_id
    ORDER BY m.created_at ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetOrCreateChat` (IN `p_user1_id` INT, IN `p_user2_id` INT, OUT `p_chat_id` INT)   BEGIN
    SELECT c.id INTO p_chat_id
    FROM chats c
    JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = p_user1_id
    JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = p_user2_id
    WHERE c.is_group = 0
    LIMIT 1;

    IF p_chat_id IS NULL THEN
        INSERT INTO chats (name, is_group) VALUES (NULL, 0);
        SET p_chat_id = LAST_INSERT_ID();
        INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user1_id);
        INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user2_id);
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MarkChatAsRead` (IN `p_chat_id` INT, IN `p_user_id` INT)   BEGIN
    UPDATE chat_participants SET last_read_at = NOW()
    WHERE chat_id = p_chat_id AND user_id = p_user_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SendMessage` (IN `p_chat_id` INT, IN `p_sender_id` INT, IN `p_content` TEXT)   BEGIN
    INSERT INTO messages (chat_id, sender_id, content)
    VALUES (p_chat_id, p_sender_id, p_content);
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UserIsParticipant` (IN `p_chat_id` INT, IN `p_user_id` INT)   BEGIN
    SELECT 1 FROM chat_participants
    WHERE chat_id = p_chat_id AND user_id = p_user_id LIMIT 1;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chats`
--

CREATE TABLE `chats` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `is_group` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Daten für Tabelle `chats`
--

INSERT INTO `chats` (`id`, `name`, `is_group`) VALUES
(1, NULL, 0),
(2, 'Gruppe 1', 1),
(3, NULL, 0),
(4, '3bAPC', 1),
(5, NULL, 0),
(6, 'Alle Philipps', 1),
(7, NULL, 0),
(8, 'Philipp + Max', 1);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `chat_participants`
--

CREATE TABLE `chat_participants` (
  `chat_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` datetime DEFAULT current_timestamp(),
  `last_read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `chat_participants`
--

INSERT INTO `chat_participants` (`chat_id`, `user_id`, `joined_at`, `last_read_at`) VALUES
(1, 1, '2026-06-02 10:00:59', '2026-06-09 14:49:43'),
(1, 2, '2026-06-02 10:00:59', '2026-06-08 19:50:37'),
(2, 1, '2026-06-02 14:12:27', '2026-06-09 14:54:06'),
(2, 2, '2026-06-02 14:12:27', '2026-06-08 19:49:10'),
(2, 3, '2026-06-02 14:12:27', '2026-06-09 15:08:14'),
(2, 4, '2026-06-02 14:12:27', '2026-06-02 20:24:55'),
(2, 5, '2026-06-02 14:12:27', NULL),
(3, 1, '2026-06-02 14:46:55', '2026-06-09 14:53:49'),
(3, 3, '2026-06-02 14:46:55', '2026-06-08 20:20:47'),
(4, 2, '2026-06-02 20:10:29', '2026-06-08 19:50:58'),
(4, 4, '2026-06-02 20:10:29', '2026-06-02 20:25:46'),
(4, 5, '2026-06-02 20:10:29', '2026-06-02 20:32:48'),
(5, 2, '2026-06-08 19:50:43', '2026-06-09 14:26:52'),
(5, 3, '2026-06-08 19:50:43', '2026-06-09 15:08:35'),
(6, 3, '2026-06-08 20:20:39', '2026-06-09 14:49:22'),
(6, 4, '2026-06-08 20:20:39', NULL),
(7, 2, '2026-06-09 14:18:22', '2026-06-09 14:18:28'),
(7, 5, '2026-06-09 14:18:22', NULL),
(8, 3, '2026-06-09 15:08:47', '2026-06-09 15:08:59'),
(8, 5, '2026-06-09 15:08:47', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `gallery_images`
--

CREATE TABLE `gallery_images` (
  `image_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `filename` varchar(255) NOT NULL COMMENT 'Gespeicherter Dateiname auf dem Server',
  `original_name` varchar(255) NOT NULL COMMENT 'Originaler Dateiname beim Upload',
  `uploaded_at` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `gallery_images`
--

INSERT INTO `gallery_images` (`image_id`, `user_id`, `filename`, `original_name`, `uploaded_at`) VALUES
(2, 1, '1781162231_1_TestBild.jpg', 'TestBild.jpg', 1781162231),
(3, 1, '1781162876_1_TestBild2.jpg', 'TestBild2.jpg', 1781162876),
(4, 1, '1781162884_1_TestBild3.jpg', 'TestBild3.jpg', 1781162884),
(5, 1, '1781165347_1_TestBild4.jpg', 'TestBild4.jpg', 1781165347);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `chat_id` int(10) UNSIGNED NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `messages`
--

INSERT INTO `messages` (`id`, `chat_id`, `sender_id`, `content`, `created_at`) VALUES
(1, 1, 2, 'Hallo', '2026-06-02 10:01:05'),
(2, 1, 1, 'Hallo! :)', '2026-06-02 10:02:27'),
(3, 1, 1, 'Wie geht es dir?', '2026-06-02 10:03:54'),
(4, 1, 2, 'Ganz gut, danke!', '2026-06-02 10:06:04'),
(5, 1, 1, 'Was machst du heute?', '2026-06-02 13:52:46'),
(6, 1, 1, 'Ich arbeite gerade!', '2026-06-02 14:02:45'),
(7, 2, 1, 'Hallo Leute!', '2026-06-02 14:12:36'),
(8, 2, 3, 'Hey, was geht?', '2026-06-02 14:13:55'),
(9, 2, 2, 'Heyoo, alles fit?', '2026-06-02 14:33:22'),
(10, 1, 1, 'Jetzt nicht mehr', '2026-06-02 15:09:18'),
(11, 1, 2, 'ich auch nicht mehr', '2026-06-02 15:12:41'),
(12, 1, 1, 'ich habe eine Frage', '2026-06-02 15:17:09'),
(13, 1, 1, 'kannst du mir helfen?', '2026-06-02 15:17:17'),
(14, 1, 2, 'natürlich', '2026-06-02 15:21:48'),
(15, 1, 2, 'was brauchst du genau?', '2026-06-02 15:21:56'),
(16, 1, 2, 'ich helfen gerne!', '2026-06-02 15:22:03'),
(17, 1, 1, 'hallo', '2026-06-02 15:34:47'),
(18, 1, 1, 'ich arbeite mit php!', '2026-06-02 18:50:52'),
(19, 1, 1, 'Das ist eine weitere Nachricht', '2026-06-02 18:54:30'),
(20, 4, 2, 'Was haben wir heute für eine Aufgaben?', '2026-06-02 20:10:52'),
(21, 4, 5, 'ich weiß es nicht ich glaube AWL?', '2026-06-02 20:24:07'),
(22, 4, 5, 'weißt du es Philipp?', '2026-06-02 20:24:15'),
(23, 4, 4, 'Wir haben heute keine Aufgabe!', '2026-06-02 20:25:45'),
(24, 4, 2, 'Danke!', '2026-06-02 20:26:16'),
(25, 1, 1, 'Hey, guten Morgen, wie gehts dir', '2026-06-08 18:05:06'),
(26, 2, 1, 'Alles Gut bei mir!!', '2026-06-08 18:06:08'),
(27, 1, 1, 'ok, gut zu wissen', '2026-06-08 19:25:00'),
(28, 1, 2, 'test', '2026-06-08 19:28:33'),
(29, 2, 2, 'test', '2026-06-08 19:28:42'),
(30, 1, 1, 'hi was geht', '2026-06-08 19:29:10'),
(31, 2, 1, 'test2', '2026-06-08 19:29:22'),
(32, 2, 2, 'test', '2026-06-08 19:33:38'),
(33, 1, 2, 'ich hoffe es geht nun', '2026-06-08 19:33:48'),
(34, 1, 2, 'test3', '2026-06-08 19:33:51'),
(35, 1, 1, 'test 4', '2026-06-08 19:38:36'),
(36, 2, 1, 'Übung wird gemacht', '2026-06-08 19:38:48'),
(37, 5, 2, 'Hallo Philipp', '2026-06-08 19:50:49'),
(38, 4, 2, 'Hallo Leute', '2026-06-08 19:50:58'),
(39, 3, 3, 'Hey was geht', '2026-06-08 20:06:47'),
(40, 2, 3, 'Hallo leute', '2026-06-08 20:06:54'),
(41, 3, 1, 'Ich schreibe eine Testnachricht', '2026-06-08 20:20:03'),
(42, 3, 1, 'was geht bei dir?', '2026-06-08 20:20:06'),
(43, 2, 1, 'hey :)', '2026-06-08 20:20:14'),
(44, 6, 3, 'Hey Philipp!', '2026-06-08 20:20:44'),
(45, 1, 1, 'ncoh ein test', '2026-06-09 13:22:53'),
(46, 7, 2, 'Hey Max!', '2026-06-09 14:18:28'),
(47, 5, 2, 'Wie geht es dir?', '2026-06-09 14:26:51'),
(48, 2, 1, 'mir ist langweilig!', '2026-06-09 14:54:06'),
(49, 5, 3, 'Mir geht es gut', '2026-06-09 15:08:26'),
(50, 5, 3, 'Ich teste gerade Funktionen!', '2026-06-09 15:08:35'),
(51, 8, 3, 'Hey Max!', '2026-06-09 15:08:58');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `notes`
--

CREATE TABLE `notes` (
  `note_id` int(11) UNSIGNED NOT NULL,
  `note_text` text NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user notes';

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL COMMENT 'auto incrementing user_id of each user, unique index',
  `session_id` varchar(48) DEFAULT NULL COMMENT 'stores session cookie id to prevent session concurrency',
  `user_name` varchar(64) NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(254) NOT NULL COMMENT 'user''s email, unique',
  `user_active` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s activation status',
  `user_deleted` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s deletion status',
  `user_account_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'user''s account type (basic, premium, etc)',
  `user_has_avatar` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1 if user has a local avatar, 0 if not',
  `user_remember_me_token` varchar(64) DEFAULT NULL COMMENT 'user''s remember-me cookie token',
  `user_creation_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the creation of user''s account',
  `user_suspension_timestamp` bigint(20) DEFAULT NULL COMMENT 'Timestamp till the end of a user suspension',
  `user_last_login_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of user''s last login',
  `user_failed_logins` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'user''s failed login attempts',
  `user_last_failed_login` int(10) DEFAULT NULL COMMENT 'unix timestamp of last failed login attempt',
  `user_activation_hash` varchar(80) DEFAULT NULL COMMENT 'user''s email verification hash string',
  `user_password_reset_hash` char(80) DEFAULT NULL COMMENT 'user''s password reset code',
  `user_password_reset_timestamp` bigint(20) DEFAULT NULL COMMENT 'timestamp of the password reset request',
  `user_provider_type` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_active`, `user_deleted`, `user_account_type`, `user_has_avatar`, `user_remember_me_token`, `user_creation_timestamp`, `user_suspension_timestamp`, `user_last_login_timestamp`, `user_failed_logins`, `user_last_failed_login`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, `user_provider_type`) VALUES
(1, 'ta3ccsavqnvi76gc6b2hkiirks', 'demo', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo@demo.com', 1, 0, 7, 0, NULL, 1422205178, NULL, 1781163982, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(2, NULL, 'demo2', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo2@demo.com', 1, 0, 2, 0, NULL, 1422205178, NULL, 1781163236, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(3, NULL, 'philipp', '$2y$10$iUhqD.7qChy4FYnrVuxDVer2baKa5DAFG.rEHRlq1/luX.papuEIO', 'philipp.breitler@email.com', 1, 0, 1, 0, NULL, 1778591579, NULL, 1781010784, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(4, NULL, 'philipp2', '$2y$10$N9dLE4m1Ox0Luu9UB1ZiDu.tVKO2mFY0LmRBq9eb/15P9QQBJ.qyG', 'philipp2.breitler@email.com', 1, 0, 1, 0, NULL, 1778591626, NULL, 1780424690, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
(5, NULL, 'max', '$2y$10$jxOx8MrSjl7EH.8HqU08PeNH5j0s3sbgWBrOj/cpmxu1k6mo8xiHy', 'muster@mann.com', 1, 0, 2, 0, NULL, 1778592415, NULL, 1781010797, 0, NULL, NULL, NULL, NULL, 'DEFAULT');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_roles`
--

CREATE TABLE `user_roles` (
  `role_id` tinyint(1) NOT NULL,
  `role_name` varchar(64) NOT NULL COMMENT 'Bezeichnung der Rolle'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='Benutzerrollen';

--
-- Daten für Tabelle `user_roles`
--

INSERT INTO `user_roles` (`role_id`, `role_name`) VALUES
(1, 'Gast'),
(2, 'normaler User'),
(7, 'Admin');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD PRIMARY KEY (`chat_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indizes für die Tabelle `gallery_images`
--
ALTER TABLE `gallery_images`
  ADD PRIMARY KEY (`image_id`);

--
-- Indizes für die Tabelle `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_id` (`chat_id`),
  ADD KEY `sender_id` (`sender_id`);

--
-- Indizes für die Tabelle `notes`
--
ALTER TABLE `notes`
  ADD PRIMARY KEY (`note_id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `fk_user_role` (`user_account_type`);

--
-- Indizes für die Tabelle `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`role_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `chats`
--
ALTER TABLE `chats`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT für Tabelle `gallery_images`
--
ALTER TABLE `gallery_images`
  MODIFY `image_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT für Tabelle `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT für Tabelle `notes`
--
ALTER TABLE `notes`
  MODIFY `note_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index', AUTO_INCREMENT=6;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `chat_participants`
--
ALTER TABLE `chat_participants`
  ADD CONSTRAINT `chat_participants_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `chat_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints der Tabelle `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`);

--
-- Constraints der Tabelle `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`user_account_type`) REFERENCES `user_roles` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
