<?php

class MessengerModel {

    public static function getOrCreateChat($user1_id, $user2_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        // Prüfen ob Chat bereits existiert (in beide Richtungen)
        $sql = "SELECT c.id FROM chats c
                JOIN chat_participants cp1 ON cp1.chat_id = c.id AND cp1.user_id = :user1_id
                JOIN chat_participants cp2 ON cp2.chat_id = c.id AND cp2.user_id = :user2_id
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
}

?>