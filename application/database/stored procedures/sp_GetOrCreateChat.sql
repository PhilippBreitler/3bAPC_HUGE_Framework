DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetOrCreateChat`(IN `p_user1_id` INT, IN `p_user2_id` INT, OUT `p_chat_id` INT)
BEGIN
    SELECT c.id INTO p_chat_id
    FROM chats c
    JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = p_user1_id
    JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = p_user2_id
    WHERE c.is_group = 0
    LIMIT 1;

    IF p_chat_id IS NULL THEN
        INSERT INTO chats (name, is_group) VALUES (NULL, 0);
        SET p_chat_id = LAST_INSERT_ID();
        INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user1_id);
        INSERT INTO chat_participants (chat_id, user_id) VALUES (p_chat_id, p_user2_id);
    END IF;
END$$
DELIMITER ;