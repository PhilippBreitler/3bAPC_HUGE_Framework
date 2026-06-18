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

        if (Request::post('submit') !== null) {
            // Formular wurde abgesendet → Listing erstellen
            $listing_id = MarketplaceModel::createListing(
                $_POST,                 // alle Textfelder
                $_FILES['photos']       // Fotos
            );

            if ($listing_id) {
                Redirect::to('marketplace/index');
            }
        }

        // Formular anzeigen (auch nach Fehler wieder anzeigen)
        $this->View->render('marketplace/create', [
            'categories' => MarketplaceModel::getCategories()
        ]);
    }

}
