DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetPhotoForDelete`(
    IN `p_photo_id` INT,
    IN `p_owner_id` INT
)
BEGIN
    SELECT p.listing_id, p.photo_filename
    FROM marketplace_photos p
    JOIN marketplace_listings l ON l.listing_id = p.listing_id
    WHERE p.photo_id = p_photo_id AND l.user_id = p_owner_id
    LIMIT 1;
END$$
DELIMITER ;