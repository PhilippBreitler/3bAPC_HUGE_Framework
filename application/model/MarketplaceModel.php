<?php

class MarketplaceModel {
    
    /**
     * Gibt alle aktiven Listings zurück (neueste zuerst).
     * Lädt auch die ID des ersten Fotos für die Vorschau.
     */
    public static function getAllListings($filters = []) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $category_id = !empty($filters['category_id']) ? (int)$filters['category_id'] : null;
        $price_min   = (isset($filters['price_min']) && $filters['price_min'] !== '') ? (float)$filters['price_min'] : null;
        $price_max   = (isset($filters['price_max']) && $filters['price_max'] !== '') ? (float)$filters['price_max'] : null;

        $query = $database->prepare("CALL sp_mp_GetAllListings(:user_id, :category_id, :price_min, :price_max)");
        $query->execute([
            ':user_id'     => Session::get('user_id'),
            ':category_id' => $category_id,
            ':price_min'   => $price_min,
            ':price_max'   => $price_max,
        ]);
        return $query->fetchAll();
    }

    /**
     * Gibt alle Listings des aktuell eingeloggten Benutzers zurück.
     */
    public static function getMyListings()
        {
            $database = DatabaseFactory::getFactory()->getConnection();

            $query = $database->prepare("CALL sp_mp_GetMyListings(:user_id)");

            $query->execute([':user_id' => Session::get('user_id')]);

            return $query->fetchAll();
        }

    /**
     * Gibt alle Kategorien zurück (für das Dropdown im Formular).
     */
    public static function getCategories()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetCategories()");
        $query->execute();

        return $query->fetchAll();
    }


    /**
     * Erstellt ein neues Listing und speichert die Fotos.
     * Gibt die ID des neuen Listings zurück, oder false bei einem Fehler.
     */
    public static function createListing($data, $files)
    {
        if (empty($data['title']) || empty($data['description']) || empty($data['price']) || empty($data['category_id'])) {
        Session::add('feedback_negative', 'Bitte alle Pflichtfelder ausfüllen.');
        return false;
        }
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            Session::add('feedback_negative', 'Bitte einen gültigen Preis eingeben.');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_CreateListing(:user_id, :category_id, :title, :description, :price, :created_at, @listing_id)");
        $query->execute([
            ':user_id'     => Session::get('user_id'),
            ':category_id' => (int)$data['category_id'],
            ':title'       => trim($data['title']),
            ':description' => trim($data['description']),
            ':price'       => (float)$data['price'],
            ':created_at'  => time(),
        ]);

        $result = $database->query("SELECT @listing_id AS listing_id")->fetch();
        $listing_id = $result->listing_id;

        if (!$listing_id) {
            Session::add('feedback_negative', 'Angebot konnte nicht erstellt werden.');
            return false;
        }

        if (!empty($files['name'][0])) {
            self::uploadPhotos($listing_id, $files);
        }

        Session::add('feedback_positive', 'Angebot erfolgreich erstellt.');
        return $listing_id;
    }


    /**
     * Speichert bis zu 3 Fotos für ein Listing.
     * Wird nur intern von createListing() aufgerufen.
     */
    public static function uploadPhotos($listing_id, $files) {
        $database = DatabaseFactory::getFactory()->getConnection();
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        $dir = Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$listing_id . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $count_query = $database->prepare("CALL sp_mp_CountPhotos(:id, @count)");
        $count_query->execute([':id' => (int)$listing_id]);
        $existingCount = (int)$database->query("SELECT @count AS cnt")->fetch()->cnt;

        $maxNew = 3 - $existingCount;
        if ($maxNew <= 0) {
            Session::add('feedback_negative', 'Bereits 3 Fotos vorhanden. Bitte erst ein Foto löschen.');
            return;
        }

        $count = min(count($files['name']), $maxNew);

        for ($i = 0; $i < $count; $i++) {
            if ($files['error'][$i] !== UPLOAD_ERR_OK || empty($files['name'][$i])) {
                continue;
            }
            if ($files['size'][$i] > 5 * 1024 * 1024) {
                Session::add('feedback_negative', 'Foto ' . ($i + 1) . ' ist zu groß (max. 5 MB).');
                continue;
            }

            $mime = $finfo->file($files['tmp_name'][$i]);
            if (!in_array($mime, $allowed_mimes)) {
                Session::add('feedback_negative', 'Foto ' . ($i + 1) . ': Nur JPG, PNG und GIF erlaubt.');
                continue;
            }

            $safeName   = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($files['name'][$i]));
            $storedName = time() . '_' . $listing_id . '_' . ($i + 1) . '_' . $safeName;

            if (!move_uploaded_file($files['tmp_name'][$i], $dir . $storedName)) {
                continue;
            }

            $query = $database->prepare("CALL sp_mp_InsertPhoto(:listing_id, :filename, :order)");
            $query->execute([
                ':listing_id' => (int)$listing_id,
                ':filename'   => $storedName,
                ':order'      => ($i + 1),
            ]);
        }
    }


    /**
     * Gibt ein einzelnes Listing anhand seiner ID zurück.
     * Gibt false zurück, wenn kein Listing gefunden wurde.
     */
    public static function getListingById($listing_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetListingById(:listing_id)");
        $query->execute([':listing_id' => (int)$listing_id]);

        return $query->fetch();
    }


    /**
     * Gibt alle Fotos eines Listings zurück.
     */
    public static function getListingPhotos($listing_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetListingPhotos(:listing_id)");
        $query->execute([':listing_id' => (int)$listing_id]);

        return $query->fetchAll();
    }


    /**
     * Gibt den absoluten Dateipfad eines Fotos zurück (für readfile()).
     * Gibt false zurück wenn das Foto nicht existiert.
     */
    public static function getPhotoPath($photo_id) {

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetPhotoPath(:photo_id)");
        $query->execute([':photo_id' => (int)$photo_id]);
        $row = $query->fetch();

        if (!$row) return false;

        return Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$row->listing_id . '/' . $row->photo_filename;
    }


    /**
     * Gibt alle Anfragen (Inquiries) zu einem Listing zurück.
     * Nur der Eigentümer des Listings darf diese einsehen.
     */
    public static function getListingInquiries($listing_id, $owner_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetListingInquiries(:listing_id, :owner_id)");
        $query->execute([
            ':listing_id' => (int)$listing_id,
            ':owner_id'   => (int)$owner_id,
        ]);

        return $query->fetchAll();
    }


    /**
     * Löscht ein Listing und alle zugehörigen Daten.
     * Gibt true zurück wenn das Löschen erfolgreich war, sonst false.
     */
    public static function deleteListing($listing_id, $owner_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_DeleteListing(:listing_id, :owner_id, @affected)");
        $query->execute([
            ':listing_id' => (int)$listing_id,
            ':owner_id'   => (int)$owner_id,
        ]);

        $result = $database->query("SELECT @affected AS affected")->fetch();
        return (int)$result->affected === 1;
    }


    /**
     * Markiert ein Listing als verkauft (setzt den Status entsprechend).
     * Gibt true zurück wenn die Aktion erfolgreich war, sonst false.
     */
    public static function markAsSold($listing_id, $owner_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_DeleteListing(:listing_id, :owner_id, @affected)");
        $query->execute([':listing_id' => (int)$listing_id, ':owner_id' => (int)$owner_id]);

        $result = $database->query("SELECT @affected AS affected")->fetch();
        return (int)$result->affected === 1;
    }



    /**
     * Aktualisiert ein bestehendes Listing mit den neuen Daten.
     * Validiert die Pflichtfelder und gibt false bei ungültigen Eingaben zurück.
     */
    public static function updateListing($listing_id, $owner_id, $data) {
        if (empty($data['title']) || empty($data['description']) || empty($data['price']) || empty($data['category_id'])) {
            Session::add('feedback_negative', 'Bitte alle Pflichtfelder ausfüllen.');
            return false;
        }
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            Session::add('feedback_negative', 'Bitte einen gültigen Preis eingeben.');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_UpdateListing(:listing_id, :owner_id, :title, :description, :price, :category_id)");
        $query->execute([
            ':listing_id'  => (int)$listing_id,
            ':owner_id'    => (int)$owner_id,
            ':title'       => trim($data['title']),
            ':description' => trim($data['description']),
            ':price'       => (float)$data['price'],
            ':category_id' => (int)$data['category_id'],
        ]);

        Session::add('feedback_positive', 'Angebot erfolgreich aktualisiert.');
        return true;
    }


    /**
     * Löscht ein einzelnes Foto eines Listings.
     * Entfernt sowohl die Datei vom Server als auch den Datenbankeintrag.
     * Gibt true zurück wenn das Löschen erfolgreich war, sonst false.
     */
    public static function deletePhoto($photo_id, $owner_id) {
        $database = DatabaseFactory::getFactory()->getConnection();

        $query = $database->prepare("CALL sp_mp_GetPhotoForDelete(:photo_id, :owner_id)");
        $query->execute([':photo_id' => (int)$photo_id, ':owner_id' => (int)$owner_id]);
        $row = $query->fetch();

        if (!$row) return false;

        $path = Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$row->listing_id . '/' . $row->photo_filename;
        if (file_exists($path)) {
            unlink($path);
        }

        $query = $database->prepare("CALL sp_mp_DeletePhoto(:photo_id)");
        $query->execute([':photo_id' => (int)$photo_id]);

        return $query->rowCount() === 1;
    }
}



