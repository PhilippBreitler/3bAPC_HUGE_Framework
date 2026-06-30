DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_CountPhotos`(
    IN `p_listing_id` INT,
    OUT `p_count` INT
)
BEGIN
    SELECT COUNT(*) INTO p_count
    FROM marketplace_photos
    WHERE listing_id = p_listing_id;
END$$
DELIMITER ;