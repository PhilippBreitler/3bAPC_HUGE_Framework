DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetMyListings`(IN `p_user_id` INT)
BEGIN
    SELECT l.listing_id, l.listing_title, l.listing_price,
           c.category_name,
           (SELECT p.photo_id FROM marketplace_photos p
            WHERE p.listing_id = l.listing_id ORDER BY p.photo_order ASC LIMIT 1) AS first_photo_id
    FROM marketplace_listings l
    JOIN marketplace_categories c ON c.category_id = l.category_id
    WHERE l.user_id = p_user_id
      AND l.listing_active = 1
    ORDER BY l.listing_creation_timestamp DESC;
END$$
DELIMITER ;