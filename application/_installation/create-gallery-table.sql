CREATE TABLE IF NOT EXISTS `huge`.`gallery_images` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL COMMENT 'Gespeicherter Dateiname auf dem Server',
  `original_name` varchar(255) NOT NULL COMMENT 'Originaler Dateiname beim Upload',
  `uploaded_at` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`image_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;