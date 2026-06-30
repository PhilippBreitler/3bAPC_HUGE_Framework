DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_InsertPhoto`(
    IN `p_listing_id` INT,
    IN `p_filename` VARCHAR(255),
    IN `p_order` INT
)
BEGIN
    INSERT INTO marketplace_photos (listing_id, photo_filename, photo_order)
    VALUES (p_listing_id, p_filename, p_order);
END$$
DELIMITER ;