<?php

class MessengerModel {

    public static function getOrCreateChat($user1_id, $user2_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        // Prüfen ob Chat bereits existiert (in beide Richtungen)
        $sql = "SELECT c.id FROM chats c
                JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = :user1_id
                JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = :user2_id
                WHERE c.is_group = 0
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':user1_id' => $user1_id, ':user2_id' => $user2_id));
        $chat = $query->fetch();

        if ($chat) {
            return $chat->id;
        }

        // Neuen Chat anlegen
        $sql = "INSERT INTO chats (name) VALUES (NULL)";
        $query = $database->prepare($sql);
        $query->execute();
        $chat_id = $database->lastInsertId();

        // Beide User als Teilnehmer eintragen
        $sql = "INSERT INTO chat_participants (chat_id, user_id) VALUES (:chat_id, :user_id)";
        $query = $database->prepare($sql);
        $query->execute(array(':chat_id' => $chat_id, ':user_id' => $user1_id));
        $query->execute(array(':chat_id' => $chat_id, ':user_id' => $user2_id));

        return $chat_id;
    }

    public static function getMessagesByChatId($chat_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT m.id, m.sender_id, m.content, m.created_at, u.user_name
                FROM messages m
                JOIN users u ON u.user_id = m.sender_id
                WHERE m.chat_id = :chat_id
                ORDER BY m.created_at ASC";
        $query = $database->prepare($sql);
        $query->execute(array(':chat_id' => $chat_id));

        return $query->fetchAll();
    }

    public static function sendMessage($chat_id, $sender_id, $content) {
        if (!$content || strlen(trim($content)) == 0) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO messages (chat_id, sender_id, content) VALUES (:chat_id, :sender_id, :content)";
        $query = $database->prepare($sql);
        $query->execute(array(
            ':chat_id'   => $chat_id,
            ':sender_id' => $sender_id,
            ':content'   => trim($content)
        ));

        return $query->rowCount() == 1;
    }

    public static function userIsParticipant($chat_id, $user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT 1 FROM chat_participants WHERE chat_id = :chat_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute(array(':chat_id' => $chat_id, ':user_id' => $user_id));

        return $query->fetch() !== false;
    }


    // VOM COPILOT ERSTELLT
    public static function createGroupChat($name, array $user_ids) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO chats (name, is_group) VALUES (:name, 1)";
        $query = $database->prepare($sql);
        $query->execute([':name' => trim($name)]);
        $chat_id = $database->lastInsertId();

        $sql = "INSERT INTO chat_participants (chat_id, user_id) VALUES (:chat_id, :user_id)";
        $query = $database->prepare($sql);
        foreach ($user_ids as $uid) {
            $query->execute([':chat_id' => $chat_id, ':user_id' => (int)$uid]);
        }
        return $chat_id;
    }

    // VOM COPILOT ERSTELLT
    public static function getChatInfo($chat_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT id, name, is_group FROM chats WHERE id = :chat_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([':chat_id' => $chat_id]);
        return $query->fetch();
    }

    // VOM COPILOT ERSTELLT
    public static function getChatsByUserId($user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT c.id, c.name, c.is_group 
                FROM chats c
                JOIN chat_participants cp ON cp.chat_id = c.id
                WHERE cp.user_id = :user_id
                ORDER BY c.id DESC";
        $query = $database->prepare($sql);
        $query->execute([':user_id' => $user_id]);
        return $query->fetchAll();
    }


    public static function markChatAsRead($chat_id, $user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE chat_participants SET last_read_at = NOW() 
                WHERE chat_id = :chat_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([':chat_id' => $chat_id, ':user_id' => $user_id]);
    }

    public static function getUnreadCountsByUserId($user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT cp.chat_id, COUNT(m.id) AS unread_count
                FROM chat_participants cp
                JOIN messages m ON m.chat_id = cp.chat_id
                WHERE cp.user_id = :user_id
                AND m.sender_id != :user_id
                AND (cp.last_read_at IS NULL OR m.created_at > cp.last_read_at)
                GROUP BY cp.chat_id";
        $query = $database->prepare($sql);
        $query->execute([':user_id' => $user_id]);
        // Gibt ein assoziatives Array [chat_id => unread_count] zurück
        $result = [];
        foreach ($query->fetchAll() as $row) {
            $result[$row->chat_id] = (int)$row->unread_count;
        }
        return $result;
    }



    public static function getDirectChatUserMap($user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT cp2.user_id AS partner_id, c.id AS chat_id
                FROM chats c
                JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = :user_id
                JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id != :user_id
                WHERE c.is_group = 0";
        $query = $database->prepare($sql);
        $query->execute([':user_id' => $user_id]);
        $result = [];
        foreach ($query->fetchAll() as $row) {
            $result[$row->partner_id] = $row->chat_id;
        }
        return $result;
    }

}

?>