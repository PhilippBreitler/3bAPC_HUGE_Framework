DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_AddChatParticipent`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user_id);
END$$
DELIMITER ;