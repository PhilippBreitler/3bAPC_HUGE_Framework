DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetCategories`()
BEGIN
    SELECT category_id, category_name
    FROM marketplace_categories
    ORDER BY category_name ASC;
END$$
DELIMITER ;