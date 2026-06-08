<?php

    class MessengerController extends Controller {

        public function __construct() {
            
            parent::__construct();
            Auth::checkAuthentication();
        }

        public function index() {

            $this->View->render('messenger/index', array(
                'users' => UserModel::getPublicProfilesOfAllUsers(),
                'chats' => MessengerModel::getChatsByUserId(Session::get('user_id')),    // VOM COPLIOT ERSTELLT
            ));
        }


        public function openChat($user_id) {

            $chat_id = MessengerModel::getOrCreateChat(Session::get('user_id'), $user_id);
            Redirect::to('messenger/showChat/' . $chat_id);

        }

        public function showChat($chat_id)
        {
            // Sicherheit: prüfen ob der eingeloggte User in diesem Chat ist
            if (!MessengerModel::userIsParticipant($chat_id, Session::get('user_id'))) {
                Redirect::to('messenger/index');
            }
            MessengerModel::markChatAsRead($chat_id, Session::get('user_id'));

            $this->View->render('messenger/chat', array(
                'messages' => MessengerModel::getMessagesByChatId($chat_id),
                'chat_id'  => $chat_id,
                'chat_info' => MessengerModel::getChatInfo($chat_id)
            ));
        }

        public function sendMessage()
        {
            $chat_id = Request::post('chat_id');

            // Sicherheit: prüfen ob der eingeloggte User in diesem Chat ist
            if (!MessengerModel::userIsParticipant($chat_id, Session::get('user_id'))) {
                Redirect::to('messenger/index');
            }

            MessengerModel::sendMessage($chat_id, Session::get('user_id'), Request::post('content'));
            Redirect::to('messenger/showChat/' . $chat_id);
        }

        // VOM COPLIOT ERSTELLT
        public function createGroup() {
            // POST: Gruppe anlegen
            if (Request::post('group_name') && Request::post('members')) {
                $members = Request::post('members'); // Array von user_ids
                // Aktuellen User immer hinzufügen
                $members[] = Session::get('user_id');
                $members = array_unique(array_map('intval', $members));

                $chat_id = MessengerModel::createGroupChat(
                    Request::post('group_name'),
                    $members
                );
                Redirect::to('messenger/showChat/' . $chat_id);
            }

            // GET: Formular anzeigen
            $this->View->render('messenger/create_group', [
                'users' => UserModel::getPublicProfilesOfAllUsers()
            ]);
        }
    }

?>