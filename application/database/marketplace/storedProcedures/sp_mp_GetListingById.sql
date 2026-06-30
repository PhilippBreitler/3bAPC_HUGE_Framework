DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetListingById`(IN `p_listing_id` INT)
BEGIN
    SELECT l.listing_id, l.listing_title, l.listing_description, l.listing_price,
           l.listing_creation_timestamp, l.user_id, l.category_id,
           c.category_name, u.user_name
    FROM marketplace_listings l
    JOIN marketplace_categories c ON c.category_id = l.category_id
    JOIN users u ON u.user_id = l.user_id
    WHERE l.listing_id = p_listing_id
      AND l.listing_active = 1
    LIMIT 1;
END$$
DELIMITER ;