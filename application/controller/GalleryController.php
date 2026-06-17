<?php

class GalleryController extends Controller {

    public function __construct() {

        parent::__construct();
        Auth::checkAuthentication();
    }

    
    public function index() {
        if (Request::post('upload') !== null) {
            GalleryModel::uploadImage($_FILES['datei']);
            Redirect::to('gallery');
        }

        $this->View->render('gallery/index', [
            'images' => GalleryModel::getAllImages()
        ]);
    }


    public function image($image_id) {
        $path = GalleryModel::getImagePath((int)$image_id);

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


    public function delete($image_id) {
        GalleryModel::deleteImage((int)$image_id);
        Redirect::to('gallery');
    }

    public function download($image_id) {
        $path = GalleryModel::getImagePath((int)$image_id);

        if (!$path || !file_exists($path)) {
            Redirect::to('error/index/404');
            return;
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($path);
        $filename = basename($path);

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($path));
        readfile($path);
        exit;
    }
}