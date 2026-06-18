DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CreateGroupChat`(IN `p_name` VARCHAR(255), OUT `p_chat_id` INT)
BEGIN
    INSERT INTO chats (name, is_group) VALUES (p_name, 1);
    SET p_chat_id = LAST_INSERT_ID();
END$$
DELIMITER ;