DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_DeletePhoto`(IN `p_photo_id` INT)
BEGIN
    DELETE FROM marketplace_photos WHERE photo_id = p_photo_id;
END$$
DELIMITER ;