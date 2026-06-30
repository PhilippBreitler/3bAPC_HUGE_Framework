--
-- Tabellenstruktur für Tabelle `marketplace_photos`
--

CREATE TABLE `marketplace_photos` (
  `photo_id` int(11) UNSIGNED NOT NULL,
  `listing_id` int(11) UNSIGNED NOT NULL COMMENT 'FK to marketplace_listings.listing_id',
  `photo_filename` varchar(255) NOT NULL,
  `photo_order` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Reihenfolge der Fotos (1-3)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='photos for marketplace listings';