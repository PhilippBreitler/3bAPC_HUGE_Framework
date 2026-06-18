<?php

class MarketplaceController extends Controller {

    public function __construct() {

        parent::__construct();
        Auth::checkAuthentication();
    }

    // Übersicht mit allen Angeboten
    public function index() {
        $this->View->render('marketplace/index', [
            'listings' => MarketplaceModel::getAllListings()
        ]);
    }


    public function create() {

        // Formular anzeigen (auch nach Fehler wieder anzeigen)
        $this->View->render('marketplace/create', [
            'categories' => MarketplaceModel::getCategories()
        ]);
    }

}
