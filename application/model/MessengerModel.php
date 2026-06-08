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

    // Alle Chats des Users holen
    $sql = "SELECT c.id, c.name, c.is_group 
            FROM chats c
            JOIN chat_participants cp ON cp.chat_id = c.id
            WHERE cp.user_id = :user_id
            ORDER BY c.id DESC";
    $query = $database->prepare($sql);
    $query->execute([':user_id' => $user_id]);
    $chats = $query->fetchAll();

    // Jeden Chat mit weiteren Infos ergänzen
    foreach ($chats as $chat) {

        // Ungelesene Nachrichten zählen
        $sql = "SELECT COUNT(*) AS unread_count
                FROM messages m
                JOIN chat_participants cp ON cp.chat_id = m.chat_id AND cp.user_id = :user_id
                WHERE m.chat_id = :chat_id
                AND m.sender_id != :sender_id
                AND (cp.last_read_at IS NULL OR m.created_at > cp.last_read_at)";
        $q = $database->prepare($sql);
        $q->execute([':user_id' => $user_id, ':chat_id' => $chat->id, ':sender_id' => $user_id]);
        $chat->unread_count = (int) $q->fetchColumn();

        // Partner-ID für Einzelchats ermitteln
        if (!$chat->is_group) {
            $sql = "SELECT user_id FROM chat_participants 
                    WHERE chat_id = :chat_id AND user_id != :user_id";
            $q = $database->prepare($sql);
            $q->execute([':chat_id' => $chat->id, ':user_id' => $user_id]);
            $partner = $q->fetch();
            $chat->partner_id = $partner ? $partner->user_id : null;
        } else {
            $chat->partner_id = null;
        }
    }

    return $chats;
}


    public static function markChatAsRead($chat_id, $user_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE chat_participants SET last_read_at = NOW() 
                WHERE chat_id = :chat_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([':chat_id' => $chat_id, ':user_id' => $user_id]);
    }

}

?>