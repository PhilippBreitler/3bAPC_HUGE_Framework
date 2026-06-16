<?php

class MarketplaceController extends Controller {

    public function __construct() {

        parent::__construct();
        Auth::checkAuthentication();
    }
}