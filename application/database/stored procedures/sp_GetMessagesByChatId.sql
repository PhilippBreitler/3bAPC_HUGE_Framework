DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetMessagesByChatId`(IN `p_chat_id` INT)
BEGIN
    SELECT m.id, m.sender_id, m.content, m.created_at, u.user_name
    FROM messages m
    JOIN users u ON u.user_id = m.sender_id
    WHERE m.chat_id = p_chat_id
    ORDER BY m.created_at ASC;
END$$
DELIMITER ;