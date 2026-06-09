<?php

class MessengerModel {


    public static function getOrCreateChat($user1_id, $user2_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_GetOrCreateChat(:user1_id, :user2_id, @chat_id)");
        $query->execute(array(':user1_id' => $user1_id, ':user2_id' => $user2_id));

        $result = $database->query("SELECT @chat_id AS chat_id")->fetch();
        return $result->chat_id;
    }


    public static function getMessagesByChatId($chat_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_GetMessagesByChatId(:chat_id)");
        $query->execute(array(':chat_id' => $chat_id));

        return $query->fetchAll();
    }


    public static function sendMessage($chat_id, $sender_id, $content) {

        if (!$content || strlen(trim($content)) == 0) {
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_SendMessage(:chat_id, :sender_id, :content)");
        $query->execute(array(
            ':chat_id'   => $chat_id,
            ':sender_id' => $sender_id,
            ':content'   => trim($content)
        ));

        return $query->rowCount() == 1;
    }


    public static function userIsParticipant($chat_id, $user_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_UserIsParticipant(:chat_id, :user_id)");
        $query->execute(array(':chat_id' => $chat_id, ':user_id' => $user_id));

        return $query->fetch() !== false;
    }


    // VOM COPILOT ERSTELLT
    public static function createGroupChat($name, array $user_ids) {
        
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_CreateGroupChat(:name, @chat_id)");
        $query->execute([':name' => trim($name)]);

        $chat_id = $database->query("SELECT @chat_id AS chat_id")->fetch()->chat_id;

        $query = $database->prepare("CALL sp_AddChatParticipant(:chat_id, :user_id)");
        foreach ($user_ids as $uid) {
            $query->execute([':chat_id' => $chat_id, ':user_id' => (int)$uid]);
        }
        return $chat_id;
    }


    // VOM COPILOT ERSTELLT
    public static function getChatInfo($chat_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_GetChatInfo(:chat_id)");
        $query->execute([':chat_id' => $chat_id]);
        return $query->fetch();
    }


    // VOM COPILOT ERSTELLT
    public static function getChatsByUserId($user_id) {

    $database = DatabaseFactory::getFactory()->getConnection();

    $query = $database->prepare("CALL sp_GetChatByUserId(:user_id)");
    $query->execute([':user_id' => $user_id]);

    return $query->fetchAll();
    }


    public static function markChatAsRead($chat_id, $user_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_MarkChatAsRead(:chat_id, :user_id)");
        $query->execute([':chat_id' => $chat_id, ':user_id' => $user_id]);
    }

}
?>