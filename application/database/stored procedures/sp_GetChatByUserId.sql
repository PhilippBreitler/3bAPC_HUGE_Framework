DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_GetChatByUserId`(IN `p_user_id` INT)
BEGIN
    SELECT
        c.id, c.name, c.is_group,
        (SELECT COUNT(*)
         FROM messages m
         JOIN chat_participants cp2 ON cp2.chat_id = m.chat_id AND cp2.user_id = p_user_id
         WHERE m.chat_id = c.id
           AND m.sender_id != p_user_id
           AND (cp2.last_read_at IS NULL OR m.created_at > cp2.last_read_at)
        ) AS unread_count,
        IF(c.is_group = 0,
            (SELECT cp3.user_id FROM chat_participants cp3
             WHERE cp3.chat_id = c.id AND cp3.user_id != p_user_id LIMIT 1),
            NULL
        ) AS partner_id
    FROM chats c
    JOIN chat_participants cp ON cp.chat_id = c.id
    WHERE cp.user_id = p_user_id
    ORDER BY c.id DESC;
END$$
DELIMITER ;