DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_SendMessage`(IN `p_chat_id` INT, IN `p_sender_id` INT, IN `p_content` TEXT)
BEGIN
    INSERT INTO messages (chat_id, sender_id, content)
    VALUES (p_chat_id, p_sender_id, p_content);
END$$
DELIMITER ;