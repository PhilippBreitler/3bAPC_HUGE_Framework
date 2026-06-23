<?php

class MarketplaceController extends Controller {

    public function __construct() {

        parent::__construct();
        Auth::checkAuthentication();
    }

    // Übersicht mit allen Angeboten
    public function index() {
        $this->View->render('marketplace/index', [
            'listings' => MarketplaceModel::getAllListings(),
            'my_listings' => MarketplaceModel::getMyListings(),
            'active_tab'  => $_GET['tab'] ?? 'all'
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


    public function photo($photo_id) {
        $path = MarketplaceModel::getPhotoPath((int)$photo_id);

        if (!$path || !file_exists($path)) {
            Redirect::to('error/index/404');
            return;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($path);

        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }

    public function view($listing_id)
    {
        $listing = MarketplaceModel::getListingById((int)$listing_id);

        if (!$listing) {
            Redirect::to('error/index/404');
            return;
        }

        $this->View->render('marketplace/view', [
            'listing' => $listing,
            'photos'  => MarketplaceModel::getListingPhotos((int)$listing_id),
        ]);
    }

}
