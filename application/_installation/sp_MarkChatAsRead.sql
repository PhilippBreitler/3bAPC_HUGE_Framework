DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_MarkChatAsRead`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    UPDATE chat_participants SET last_read_at = NOW()
    WHERE chat_id = p_chat_id AND user_id = p_user_id;
END$$
DELIMITER ;