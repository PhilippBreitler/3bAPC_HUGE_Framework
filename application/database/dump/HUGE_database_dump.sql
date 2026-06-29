-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server-Version:               13.0.1-MariaDB - MariaDB Server
-- Server-Betriebssystem:        Win64
-- HeidiSQL Version:             12.17.0.7270
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Exportiere Datenbank-Struktur für huge
CREATE DATABASE IF NOT EXISTS `huge` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_uca1400_ai_ci */;
USE `huge`;

-- Exportiere Struktur von Tabelle huge.chat_participants
CREATE TABLE IF NOT EXISTS `chat_participants` (
  `chat_id` int(10) unsigned NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` datetime DEFAULT current_timestamp(),
  `last_read_at` datetime DEFAULT NULL,
  PRIMARY KEY (`chat_id`,`user_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `chat_participants_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  CONSTRAINT `chat_participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Exportiere Daten aus Tabelle huge.chat_participants: ~30 rows (ungefähr)
DELETE FROM `chat_participants`;
INSERT INTO `chat_participants` (`chat_id`, `user_id`, `joined_at`, `last_read_at`) VALUES
	(1, 1, '2026-06-02 10:00:59', '2026-06-23 15:28:35'),
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
	(5, 2, '2026-06-08 19:50:43', '2026-06-23 15:35:37'),
	(5, 3, '2026-06-08 19:50:43', '2026-06-09 15:08:35'),
	(6, 3, '2026-06-08 20:20:39', '2026-06-09 14:49:22'),
	(6, 4, '2026-06-08 20:20:39', NULL),
	(7, 2, '2026-06-09 14:18:22', '2026-06-09 14:18:28'),
	(7, 5, '2026-06-09 14:18:22', NULL),
	(8, 3, '2026-06-09 15:08:47', '2026-06-09 15:08:59'),
	(8, 5, '2026-06-09 15:08:47', NULL),
	(9, 1, '2026-06-23 15:28:50', '2026-06-23 15:29:11'),
	(9, 2, '2026-06-23 15:28:50', NULL),
	(10, 2, '2026-06-23 15:31:55', '2026-06-25 07:42:47'),
	(10, 3, '2026-06-23 15:31:55', '2026-06-25 07:41:16'),
	(11, 1, '2026-06-25 09:39:47', '2026-06-25 09:41:26'),
	(11, 2, '2026-06-25 09:39:47', '2026-06-25 09:40:25'),
	(12, 1, '2026-06-29 18:07:19', '2026-06-29 18:23:39'),
	(12, 2, '2026-06-29 18:07:19', '2026-06-29 18:07:19'),
	(13, 1, '2026-06-29 19:47:11', '2026-06-29 19:47:22'),
	(13, 2, '2026-06-29 19:47:11', '2026-06-29 19:48:34');

-- Exportiere Struktur von Tabelle huge.chats
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `is_group` tinyint(1) NOT NULL DEFAULT 0,
  `listing_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Exportiere Daten aus Tabelle huge.chats: ~10 rows (ungefähr)
DELETE FROM `chats`;
INSERT INTO `chats` (`id`, `name`, `is_group`, `listing_id`) VALUES
	(1, NULL, 0, NULL),
	(2, 'Gruppe 1', 1, NULL),
	(3, NULL, 0, NULL),
	(4, '3bAPC', 1, NULL),
	(5, NULL, 0, NULL),
	(6, 'Alle Philipps', 1, NULL),
	(7, NULL, 0, NULL),
	(8, 'Philipp + Max', 1, NULL),
	(9, 'Lacoste Schuhe', 0, 2),
	(10, 'Lacoste Schuhe', 0, 2),
	(11, 'Villa', 0, 5),
	(12, 'Alte Villa', 0, 6),
	(13, 'Computer MIFCOM', 0, 8);

-- Exportiere Struktur von Tabelle huge.gallery_images
CREATE TABLE IF NOT EXISTS `gallery_images` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL COMMENT 'Gespeicherter Dateiname auf dem Server',
  `original_name` varchar(255) NOT NULL COMMENT 'Originaler Dateiname beim Upload',
  `uploaded_at` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Exportiere Daten aus Tabelle huge.gallery_images: ~7 rows (ungefähr)
DELETE FROM `gallery_images`;
INSERT INTO `gallery_images` (`image_id`, `user_id`, `filename`, `original_name`, `uploaded_at`) VALUES
	(2, 1, '1781162231_1_TestBild.jpg', 'TestBild.jpg', 1781162231),
	(4, 1, '1781162884_1_TestBild3.jpg', 'TestBild3.jpg', 1781162884),
	(7, 1, '1781720032_1_TestBild6.jpg', 'TestBild6.jpg', 1781720032),
	(8, 1, '1781720042_1_Testbild7.jpg', 'Testbild7.jpg', 1781720042),
	(10, 1, '1781720129_1_TestBild5.jpg', 'TestBild5.jpg', 1781720129),
	(11, 2, '1781722283_2_TestBild5.jpg', 'TestBild5.jpg', 1781722283),
	(12, 2, '1781722290_2_Testbild7.jpg', 'Testbild7.jpg', 1781722290);

-- Exportiere Struktur von Tabelle huge.marketplace_categories
CREATE TABLE IF NOT EXISTS `marketplace_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `category_name` varchar(100) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='marketplace categories';

-- Exportiere Daten aus Tabelle huge.marketplace_categories: ~5 rows (ungefähr)
DELETE FROM `marketplace_categories`;
INSERT INTO `marketplace_categories` (`category_id`, `category_name`) VALUES
	(1, 'Elektronik'),
	(2, 'Kleidung'),
	(3, 'Bücher'),
	(4, 'Sport & Freizeit'),
	(5, 'Sonstiges');

-- Exportiere Struktur von Tabelle huge.marketplace_listings
CREATE TABLE IF NOT EXISTS `marketplace_listings` (
  `listing_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL COMMENT 'FK to users.user_id',
  `category_id` int(11) unsigned NOT NULL COMMENT 'FK to marketplace_categories.category_id',
  `listing_title` varchar(150) NOT NULL,
  `listing_description` text NOT NULL,
  `listing_price` decimal(10,2) unsigned NOT NULL,
  `listing_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = aktiv, 0 = gelöscht/verkauft',
  `listing_creation_timestamp` bigint(20) NOT NULL,
  PRIMARY KEY (`listing_id`),
  KEY `fk_listing_user` (`user_id`),
  KEY `fk_listing_category` (`category_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='marketplace listings';

-- Exportiere Daten aus Tabelle huge.marketplace_listings: ~11 rows (ungefähr)
DELETE FROM `marketplace_listings`;
INSERT INTO `marketplace_listings` (`listing_id`, `user_id`, `category_id`, `listing_title`, `listing_description`, `listing_price`, `listing_active`, `listing_creation_timestamp`) VALUES
	(1, 1, 5, 'OBI Leiter', 'Ich habe eine fast nicht gebrauchte Leiter zu verkaufen!\r\nEinmal auf der Baustelle verwendet!', 100.00, 0, 1782215089),
	(2, 2, 4, 'Lacoste Schuhe', 'Ich habe die neuen Lacoste Schuhe zu verkaufen!\r\nEinmal anprobiert und realisiert, dass sie mir doch nicht passen :(', 40.00, 0, 1782215223),
	(3, 1, 5, 'Grüne leere Glasflasche', 'Leere grüne Glasflasche\r\ngebraucht\r\nLieferung möglich', 5.00, 0, 1782220510),
	(4, 1, 4, 'Fußball', 'Das ist ein alter Fußball!', 30.00, 1, 1782221022),
	(5, 1, 5, 'Villa', 'Alte Villa zu verkaufen\r\nBester Zustand', 100000.00, 0, 1782373103),
	(6, 1, 5, 'Alte Villa', 'Ich verkaufe meine alte Villa!\r\nGebaut 1920', 99999.90, 1, 1782375327),
	(7, 2, 1, 'Samsung S25', 'Altes, bereits gebrauchtes Samsung Handy!\r\nPreis kann verhandelt werden.', 150.00, 1, 1782753716),
	(8, 2, 1, 'Computer MIFCOM', 'Neuer MIFCOM Computer\r\nSpecs:\r\n....\r\n\r\nPreis auf Verhandlungsbasis!', 800.00, 0, 1782753988),
	(9, 2, 1, 'Computer', 'Das ist ein Computer!', 500.00, 1, 1782755426),
	(10, 1, 5, 'Flasche', 'Das ist eine grüne Flasche', 10.00, 0, 1782757261),
	(11, 1, 3, 'Test', 'Das ist eine Beschreibung', 0.01, 0, 1782758653),
	(12, 1, 3, 'Test', 'wefwefwefwef', 34.00, 0, 1782758761);

-- Exportiere Struktur von Tabelle huge.marketplace_photos
CREATE TABLE IF NOT EXISTS `marketplace_photos` (
  `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `listing_id` int(11) unsigned NOT NULL COMMENT 'FK to marketplace_listings.listing_id',
  `photo_filename` varchar(255) NOT NULL,
  `photo_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Reihenfolge der Fotos (1-3)',
  PRIMARY KEY (`photo_id`),
  KEY `fk_photo_listing` (`listing_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='photos for marketplace listings';

-- Exportiere Daten aus Tabelle huge.marketplace_photos: ~15 rows (ungefähr)
DELETE FROM `marketplace_photos`;
INSERT INTO `marketplace_photos` (`photo_id`, `listing_id`, `photo_filename`, `photo_order`) VALUES
	(1, 1, '1782215089_1_1_Leiter.jpg', 1),
	(2, 1, '1782215089_1_2_Leiter2.jpg', 2),
	(3, 1, '1782215089_1_3_Leiter3.jpg', 3),
	(4, 2, '1782215223_2_1_Sapatos_de_couro_marrom.jpg', 1),
	(5, 3, '1782220510_3_1_Leere_Glasflasche.jpg', 1),
	(6, 4, '1782221022_4_1_Fussball__1_.jpg', 1),
	(7, 5, '1782373103_5_1_Haus.jpg', 1),
	(11, 4, '1782749903_4_1_Sapatos_de_couro_marrom.jpg', 1),
	(14, 7, '1782753716_7_1_SamsungS25.jpg', 1),
	(15, 7, '1782753716_7_2_SamsungS25_2.jpg', 2),
	(16, 7, '1782753716_7_3_SamsungS25_3.jpg', 3),
	(17, 8, '1782753988_8_1_computer_1.jpg', 1),
	(18, 8, '1782753988_8_2_computer_3.jpg', 2),
	(19, 9, '1782755426_9_1_computer_1.jpg', 1),
	(20, 6, '1782757015_6_1_Haus.jpg', 1);

-- Exportiere Struktur von Tabelle huge.messages
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(10) unsigned NOT NULL,
  `sender_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `chat_id` (`chat_id`),
  KEY `sender_id` (`sender_id`),
  CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`),
  CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

-- Exportiere Daten aus Tabelle huge.messages: ~53 rows (ungefähr)
DELETE FROM `messages`;
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
	(51, 8, 3, 'Hey Max!', '2026-06-09 15:08:58'),
	(52, 9, 1, 'Hallo, ist der Schuh noch frei?', '2026-06-23 15:29:02'),
	(53, 10, 3, 'Ich habe ihr Angebot gesehen, ist dieser noch frei? :)', '2026-06-23 15:32:20'),
	(54, 10, 2, 'Ja, der Schuh ist noch frei!', '2026-06-25 07:40:21'),
	(55, 10, 3, 'Sehr cool, dann kaufe ich ihn :)', '2026-06-25 07:41:16'),
	(56, 10, 2, 'Ich schicke dir die Schuhe!', '2026-06-25 07:42:47'),
	(57, 11, 2, 'Hallo, ich habe dein Angebot gesehen und möchte eine Haus kaufen :)', '2026-06-25 09:40:25'),
	(58, 11, 1, 'Sehr gerne, ich schicke, ich schicke es dir zu!', '2026-06-25 09:41:26'),
	(59, 13, 1, 'Hey Bro, bitte Die Computer', '2026-06-29 19:47:22'),
	(60, 13, 2, 'Safe, lass machen', '2026-06-29 19:48:34');

-- Exportiere Struktur von Tabelle huge.notes
CREATE TABLE IF NOT EXISTS `notes` (
  `note_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `note_text` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='user notes';

-- Exportiere Daten aus Tabelle huge.notes: ~0 rows (ungefähr)
DELETE FROM `notes`;

-- Exportiere Struktur von Prozedur huge.sp_AddChatParticipant
DELIMITER //
CREATE PROCEDURE `sp_AddChatParticipant`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user_id);
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_CreateGroupChat
DELIMITER //
CREATE PROCEDURE `sp_CreateGroupChat`(IN `p_name` VARCHAR(255), OUT `p_chat_id` INT)
BEGIN
    INSERT INTO chats (name, is_group) VALUES (p_name, 1);
    SET p_chat_id = LAST_INSERT_ID();
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_GetChatByUserId
DELIMITER //
CREATE PROCEDURE `sp_GetChatByUserId`(IN `p_user_id` INT)
BEGIN
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
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_GetChatInfo
DELIMITER //
CREATE PROCEDURE `sp_GetChatInfo`(IN `p_chat_id` INT)
BEGIN
    SELECT id, name, is_group FROM chats WHERE id = p_chat_id LIMIT 1;
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_GetMessagesByChatId
DELIMITER //
CREATE PROCEDURE `sp_GetMessagesByChatId`(IN `p_chat_id` INT)
BEGIN
    SELECT m.id, m.sender_id, m.content, m.created_at, u.user_name
    FROM messages m
    JOIN users u ON u.user_id = m.sender_id
    WHERE m.chat_id = p_chat_id
    ORDER BY m.created_at ASC;
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_GetOrCreateChat
DELIMITER //
CREATE PROCEDURE `sp_GetOrCreateChat`(IN `p_user1_id` INT, IN `p_user2_id` INT, OUT `p_chat_id` INT)
BEGIN
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
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_MarkChatAsRead
DELIMITER //
CREATE PROCEDURE `sp_MarkChatAsRead`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    UPDATE chat_participants SET last_read_at = NOW()
    WHERE chat_id = p_chat_id AND user_id = p_user_id;
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_SendMessage
DELIMITER //
CREATE PROCEDURE `sp_SendMessage`(IN `p_chat_id` INT, IN `p_sender_id` INT, IN `p_content` TEXT)
BEGIN
    INSERT INTO messages (chat_id, sender_id, content)
    VALUES (p_chat_id, p_sender_id, p_content);
END//
DELIMITER ;

-- Exportiere Struktur von Prozedur huge.sp_UserIsParticipant
DELIMITER //
CREATE PROCEDURE `sp_UserIsParticipant`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    SELECT 1 FROM chat_participants
    WHERE chat_id = p_chat_id AND user_id = p_user_id LIMIT 1;
END//
DELIMITER ;

-- Exportiere Struktur von Tabelle huge.user_roles
CREATE TABLE IF NOT EXISTS `user_roles` (
  `role_id` tinyint(1) NOT NULL,
  `role_name` varchar(64) NOT NULL COMMENT 'Bezeichnung der Rolle',
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='Benutzerrollen';

-- Exportiere Daten aus Tabelle huge.user_roles: ~3 rows (ungefähr)
DELETE FROM `user_roles`;
INSERT INTO `user_roles` (`role_id`, `role_name`) VALUES
	(1, 'Gast'),
	(2, 'normaler User'),
	(7, 'Admin');

-- Exportiere Struktur von Tabelle huge.users
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
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
  `user_provider_type` text DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`),
  KEY `fk_user_role` (`user_account_type`),
  CONSTRAINT `fk_user_role` FOREIGN KEY (`user_account_type`) REFERENCES `user_roles` (`role_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci COMMENT='user data';

-- Exportiere Daten aus Tabelle huge.users: ~5 rows (ungefähr)
DELETE FROM `users`;
INSERT INTO `users` (`user_id`, `session_id`, `user_name`, `user_password_hash`, `user_email`, `user_active`, `user_deleted`, `user_account_type`, `user_has_avatar`, `user_remember_me_token`, `user_creation_timestamp`, `user_suspension_timestamp`, `user_last_login_timestamp`, `user_failed_logins`, `user_last_failed_login`, `user_activation_hash`, `user_password_reset_hash`, `user_password_reset_timestamp`, `user_provider_type`) VALUES
	(1, 'maak9qps9nho8k1p1celjj197h', 'demo', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo@demo.com', 1, 0, 7, 0, NULL, 1422205178, NULL, 1782755443, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
	(2, NULL, 'demo2', '$2y$10$OvprunjvKOOhM1h9bzMPs.vuwGIsOqZbw88rzSyGCTJTcE61g5WXi', 'demo2@demo.com', 1, 0, 2, 0, NULL, 1422205178, NULL, 1782755261, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
	(3, NULL, 'philipp', '$2y$10$iUhqD.7qChy4FYnrVuxDVer2baKa5DAFG.rEHRlq1/luX.papuEIO', 'philipp.breitler@email.com', 1, 0, 1, 0, NULL, 1778591579, NULL, 1782366261, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
	(4, NULL, 'philipp2', '$2y$10$N9dLE4m1Ox0Luu9UB1ZiDu.tVKO2mFY0LmRBq9eb/15P9QQBJ.qyG', 'philipp2.breitler@email.com', 1, 0, 1, 0, NULL, 1778591626, NULL, 1780424690, 0, NULL, NULL, NULL, NULL, 'DEFAULT'),
	(5, NULL, 'max', '$2y$10$jxOx8MrSjl7EH.8HqU08PeNH5j0s3sbgWBrOj/cpmxu1k6mo8xiHy', 'muster@mann.com', 1, 0, 2, 0, NULL, 1778592415, NULL, 1781010797, 0, NULL, NULL, NULL, NULL, 'DEFAULT');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
