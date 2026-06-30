DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_UpdateListing`(
    IN `p_listing_id` INT,
    IN `p_owner_id` INT,
    IN `p_title` VARCHAR(150),
    IN `p_description` TEXT,
    IN `p_price` DECIMAL(10,2),
    IN `p_category_id` INT
)
BEGIN
    UPDATE marketplace_listings
    SET listing_title       = p_title,
        listing_description = p_description,
        listing_price       = p_price,
        category_id         = p_category_id
    WHERE listing_id = p_listing_id
      AND user_id    = p_owner_id;
END$$
DELIMITER ;