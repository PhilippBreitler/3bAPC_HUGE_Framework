DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetAllListings`(
    IN `p_user_id` INT,
    IN `p_category_id` INT,       -- NULL = kein Filter
    IN `p_price_min` DECIMAL(10,2), -- NULL = kein Filter
    IN `p_price_max` DECIMAL(10,2)  -- NULL = kein Filter
)
BEGIN
    SELECT l.listing_id, l.listing_title, l.listing_price,
           c.category_name, u.user_name,
           (SELECT p.photo_id FROM marketplace_photos p
            WHERE p.listing_id = l.listing_id ORDER BY p.photo_order ASC LIMIT 1) AS first_photo_id
    FROM marketplace_listings l
    JOIN marketplace_categories c ON c.category_id = l.category_id
    JOIN users u ON u.user_id = l.user_id
    WHERE l.listing_active = 1
      AND l.user_id != p_user_id
      AND (p_category_id IS NULL OR l.category_id = p_category_id)
      AND (p_price_min IS NULL OR l.listing_price >= p_price_min)
      AND (p_price_max IS NULL OR l.listing_price <= p_price_max)
    ORDER BY l.listing_creation_timestamp DESC;
END$$
DELIMITER ;