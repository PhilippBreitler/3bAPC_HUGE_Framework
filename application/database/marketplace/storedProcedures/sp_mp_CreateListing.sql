DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_CreateListing`(
    IN `p_user_id` INT,
    IN `p_category_id` INT,
    IN `p_title` VARCHAR(150),
    IN `p_description` TEXT,
    IN `p_price` DECIMAL(10,2),
    IN `p_created_at` BIGINT,
    OUT `p_listing_id` INT
)
BEGIN
    INSERT INTO marketplace_listings
        (user_id, category_id, listing_title, listing_description, listing_price, listing_creation_timestamp)
    VALUES
        (p_user_id, p_category_id, p_title, p_description, p_price, p_created_at);

    SET p_listing_id = LAST_INSERT_ID();
END$$
DELIMITER ;