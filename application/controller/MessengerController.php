<?php

    class MessengerController extends Controller {

        public function __construct() {
            
            parent::__construct();
            Auth::checkAuthentication();
        }

        public function index() {

        $this->View->render('messenger/index', array(
            'users' => UserModel::getPublicProfilesOfAllUsers()
        ));
        }
    }

?>