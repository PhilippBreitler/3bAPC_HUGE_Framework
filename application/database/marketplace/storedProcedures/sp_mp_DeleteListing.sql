DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_DeleteListing`(
    IN `p_listing_id` INT,
    IN `p_owner_id` INT,
    OUT `p_affected` INT
)
BEGIN
    UPDATE marketplace_listings
    SET listing_active = 0
    WHERE listing_id = p_listing_id AND user_id = p_owner_id;

    SET p_affected = ROW_COUNT();
END$$
DELIMITER ;