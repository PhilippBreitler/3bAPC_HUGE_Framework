--
-- Tabellenstruktur für Tabelle `marketplace_categories`
--

CREATE TABLE `marketplace_categories` (
  `category_id` int(11) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='marketplace categories';

--
-- Daten für Tabelle `marketplace_categories`
--

INSERT INTO `marketplace_categories` (`category_id`, `category_name`) VALUES
(1, 'Elektronik'),
(2, 'Kleidung'),
(3, 'Bücher'),
(4, 'Sport & Freizeit'),
(5, 'Sonstiges');