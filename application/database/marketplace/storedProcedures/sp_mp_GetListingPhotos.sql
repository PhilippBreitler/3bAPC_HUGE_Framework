DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetListingPhotos`(IN `p_listing_id` INT)
BEGIN
    SELECT photo_id FROM marketplace_photos
    WHERE listing_id = p_listing_id
    ORDER BY photo_order ASC;
END$$
DELIMITER ;