
DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetPhotoPath`(IN `p_photo_id` INT)
BEGIN
    SELECT listing_id, photo_filename
    FROM marketplace_photos
    WHERE photo_id = p_photo_id
    LIMIT 1;
END$$
DELIMITER ;