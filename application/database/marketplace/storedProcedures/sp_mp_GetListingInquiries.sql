DELIMITER $$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_mp_GetListingInquiries`(
    IN `p_listing_id` INT,
    IN `p_owner_id` INT
)
BEGIN
    SELECT c.id AS chat_id,
           u.user_name AS buyer_name,
           COUNT(CASE
               WHEN m.sender_id != p_owner_id
                   AND (cp_owner.last_read_at IS NULL OR m.created_at > cp_owner.last_read_at)
               THEN 1
           END) AS unread_count
    FROM chats c
    JOIN chat_participants cp_owner ON cp_owner.chat_id = c.id AND cp_owner.user_id = p_owner_id
    JOIN chat_participants cp_buyer ON cp_buyer.chat_id = c.id AND cp_buyer.user_id != p_owner_id
    JOIN users u ON u.user_id = cp_buyer.user_id
    LEFT JOIN messages m ON m.chat_id = c.id
    WHERE c.listing_id = p_listing_id
    GROUP BY c.id, u.user_name
    ORDER BY unread_count DESC, c.id DESC;
END$$
DELIMITER ;