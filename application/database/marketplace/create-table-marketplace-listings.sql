--
-- Tabellenstruktur für Tabelle `marketplace_listings`
--

CREATE TABLE `marketplace_listings` (
  `listing_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'FK to users.user_id',
  `category_id` int(11) UNSIGNED NOT NULL COMMENT 'FK to marketplace_categories.category_id',
  `listing_title` varchar(150) NOT NULL,
  `listing_description` text NOT NULL,
  `listing_price` decimal(10,2) UNSIGNED NOT NULL,
  `listing_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = aktiv, 0 = gelöscht/verkauft',
  `listing_creation_timestamp` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='marketplace listings';