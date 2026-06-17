<?php

class GalleryModel {

    public static function uploadImage($file) {

        // 1) Upload-Fehler prüfen
        if ($file['error'] !== UPLOAD_ERR_OK) {
            Session::add('feedback_negative', 'Upload fehlgeschlagen.');
            return false;
        }

        // 2) Dateigröße prüfen (max. 5 MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            Session::add('feedback_negative', 'Datei zu groß (max. 5 MB).');
            return false;
        }

        // 3) MIME-Type aus Dateiinhalt prüfen (NICHT den vom Browser!)
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($file['tmp_name']);
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($mime, $allowed)) {
            Session::add('feedback_negative', 'Nur JPG, PNG und GIF erlaubt.');
            return false;
        }

        // 4) Sicheren Dateinamen erzeugen
        $originalName = basename($file['name']);
        $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
        $storedName = time() . '_' . Session::get('user_id') . '_' . $safeName;


        // 5) Datei außerhalb des Webroot speichern
        $userDir = Config::get('PATH_USERPICTURES') . Session::get('user_id') . '/';
        if (!is_dir($userDir)) {
            mkdir($userDir, 0755, true);
        }
        $destination = $userDir . $storedName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            Session::add('feedback_negative', 'Datei konnte nicht gespeichert werden.');
            return false;
        }

        // 6) Eintrag in der Datenbank
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "INSERT INTO gallery_images (user_id, filename, original_name, uploaded_at) 
                VALUES (:user_id, :filename, :original_name, :uploaded_at)";
        $query = $database->prepare($sql);
        $query->execute([
            ':user_id'       => Session::get('user_id'),
            ':filename'      => $storedName,
            ':original_name' => $originalName,
            ':uploaded_at'   => time()
        ]);

        if ($query->rowCount() == 1) {
            Session::add('feedback_positive', 'Bild erfolgreich hochgeladen.');
            return true;
        }

        return false;
    }

    public static function getAllImages() {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT image_id, filename, original_name, uploaded_at 
                FROM gallery_images WHERE user_id = :user_id ORDER BY uploaded_at DESC";
        $query = $database->prepare($sql);
        $query->execute([':user_id' => Session::get('user_id')]);
        return $query->fetchAll();
    }

    public static function getImagePath($image_id) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "SELECT filename FROM gallery_images 
                WHERE image_id = :image_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([
            ':image_id' => $image_id,
            ':user_id'  => Session::get('user_id')
        ]);
        $row = $query->fetch();

        if (!$row) return false;
        return Config::get('PATH_USERPICTURES') . Session::get('user_id') . '/' . $row->filename;
    }


    public static function deleteImage($image_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT filename FROM gallery_images 
                WHERE image_id = :image_id AND user_id = :user_id LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([
            ':image_id' => (int)$image_id,
            ':user_id'  => Session::get('user_id')
        ]);
        $row = $query->fetch();

        if (!$row) {
            Session::add('feedback_negative', 'Bild nicht gefunden.');
            return false;
        }

        // Datei vom Server löschen
        $path = Config::get('PATH_USERPICTURES') . Session::get('user_id') . '/' . $row->filename;
        if (file_exists($path)) {
            unlink($path);
        }

        // DB-Eintrag löschen
        $sql = "DELETE FROM gallery_images WHERE image_id = :image_id AND user_id = :user_id";
        $query = $database->prepare($sql);
        $query->execute([
            ':image_id' => (int)$image_id,
            ':user_id'  => Session::get('user_id')
        ]);

        Session::add('feedback_positive', 'Bild gelöscht.');
        return true;
    }

}