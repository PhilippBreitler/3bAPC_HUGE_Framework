DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UserIsParticipant`(IN `p_chat_id` INT, IN `p_user_id` INT)
BEGIN
    SELECT 1 FROM chat_participants
    WHERE chat_id = p_chat_id AND user_id = p_user_id LIMIT 1;
END$$
DELIMITER ;