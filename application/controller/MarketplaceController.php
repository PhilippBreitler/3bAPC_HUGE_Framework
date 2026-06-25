<?php

class MarketplaceController extends Controller {

    public function __construct() {

        parent::__construct();
        Auth::checkAuthentication();
    }

    // Übersicht mit allen Angeboten
    public function index() {
        $filters = [
            'category_id' => $_GET['category_id'] ?? null,
            'price_min'   => $_GET['price_min']   ?? null,
            'price_max'   => $_GET['price_max']   ?? null,
        ];

        $this->View->render('marketplace/index', [
            'listings' => MarketplaceModel::getAllListings($filters),
            'my_listings' => MarketplaceModel::getMyListings(),
            'categories'  => MarketplaceModel::getCategories(),
            'active_tab'  => $_GET['tab'] ?? 'all',
            'filters'     => $filters
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

    public function contactSeller($listing_id)
    {
        $listing = MarketplaceModel::getListingById((int)$listing_id);

        if (!$listing || $listing->user_id == Session::get('user_id')) {
            Redirect::to('marketplace/index');
            return;
        }

        $chat_id = MessengerModel::getOrCreateListingChat(
            Session::get('user_id'),
            $listing->user_id,
            (int)$listing_id,
            $listing->listing_title
        );
        Redirect::to('messenger/showChat/' . $chat_id);
    }


    public function inquiries($listing_id)
    {
        $listing = MarketplaceModel::getListingById((int)$listing_id);

        // Nur der Eigentümer darf die Anfragen sehen
        if (!$listing || $listing->user_id != Session::get('user_id')) {
            Redirect::to('marketplace/index?tab=mine');
            return;
        }

        $this->View->render('marketplace/inquiries', [
            'listing'    => $listing,
            'inquiries'  => MarketplaceModel::getListingInquiries((int)$listing_id, Session::get('user_id')),
        ]);
    }

    public function delete($listing_id)
    {
        $listing = MarketplaceModel::getListingById((int)$listing_id);

        if (!$listing || $listing->user_id != Session::get('user_id')) {
            Redirect::to('marketplace/index?tab=mine');
            return;
        }

        MarketplaceModel::deleteListing((int)$listing_id, Session::get('user_id'));
        Session::add('feedback_positive', 'Angebot wurde entfernt.');
        Redirect::to('marketplace/index?tab=mine');
    }



    public function edit($listing_id)
    {
        $listing = MarketplaceModel::getListingById((int)$listing_id);

        if (!$listing || $listing->user_id != Session::get('user_id')) {
            Redirect::to('marketplace/index?tab=mine');
            return;
        }

        if (Request::post('submit') !== null) {
            $success = MarketplaceModel::updateListing((int)$listing_id, Session::get('user_id'), $_POST);
            if ($success) {
                Redirect::to('marketplace/view/' . $listing_id);
            }
        }

        $this->View->render('marketplace/edit', [
            'listing'    => $listing,
            'categories' => MarketplaceModel::getCategories(),
        ]);
    }
}
