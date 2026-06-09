DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetChatInfo`(IN `p_chat_id` INT)
BEGIN
    SELECT id, name, is_group FROM chats WHERE id = p_chat_id LIMIT 1;
END$$
DELIMITER ;