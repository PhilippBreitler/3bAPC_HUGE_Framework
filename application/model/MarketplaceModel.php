<?php

class MarketplaceModel {
    
    /**
     * Gibt alle aktiven Listings zurück (neueste zuerst).
     * Lädt auch die ID des ersten Fotos für die Vorschau.
     */
    public static function getAllListings($filters = [])
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $where  = ['l.listing_active = 1', 'l.user_id != :user_id'];
        $params = [':user_id' => Session::get('user_id')];

        if (!empty($filters['category_id'])) {
            $where[] = 'l.category_id = :category_id';
            $params[':category_id'] = (int)$filters['category_id'];
        }
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $where[] = 'l.listing_price >= :price_min';
            $params[':price_min'] = (float)$filters['price_min'];
        }
        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $where[] = 'l.listing_price <= :price_max';
            $params[':price_max'] = (float)$filters['price_max'];
        }

        $sql = "SELECT l.listing_id, l.listing_title, l.listing_price,
                       c.category_name, u.user_name,
                       (SELECT p.photo_id FROM marketplace_photos p
                        WHERE p.listing_id = l.listing_id ORDER BY p.photo_order ASC LIMIT 1) AS first_photo_id
                FROM marketplace_listings l
                JOIN marketplace_categories c ON c.category_id = l.category_id
                JOIN users u ON u.user_id = l.user_id
                -- WHERE l.listing_active = 1
                --     AND l.user_id != :user_id
                WHERE " . implode(' AND ', $where) . "
                ORDER BY l.listing_creation_timestamp DESC";

        $query = $database->prepare($sql);
        // $query->execute([':user_id' => Session::get('user_id')]);
        $query->execute($params);

        return $query->fetchAll();
    }

    public static function getMyListings()
        {
            $database = DatabaseFactory::getFactory()->getConnection();

            $sql = "SELECT l.listing_id, l.listing_title, l.listing_price,
                        c.category_name,
                        (SELECT p.photo_id FROM marketplace_photos p
                            WHERE p.listing_id = l.listing_id ORDER BY p.photo_order ASC LIMIT 1) AS first_photo_id
                    FROM marketplace_listings l
                    JOIN marketplace_categories c ON c.category_id = l.category_id
                    WHERE l.user_id = :user_id
                    AND l.listing_active = 1
                    ORDER BY l.listing_creation_timestamp DESC";

            $query = $database->prepare($sql);
            $query->execute([':user_id' => Session::get('user_id')]);

            return $query->fetchAll();
        }

    /**
     * Gibt alle Kategorien zurück (für das Dropdown im Formular).
     */
    public static function getCategories()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT category_id, category_name FROM marketplace_categories ORDER BY category_name ASC";
        $query = $database->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    }


    /**
     * Erstellt ein neues Listing und speichert die Fotos.
     * Gibt die ID des neuen Listings zurück, oder false bei einem Fehler.
     */
    public static function createListing($data, $files)
    {
        // Pflichtfelder prüfen
        if (empty($data['title']) || empty($data['description']) || empty($data['price']) || empty($data['category_id'])) {
            Session::add('feedback_negative', 'Bitte alle Pflichtfelder ausfüllen.');
            return false;
        }

        // Preis muss eine positive Zahl sein
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            Session::add('feedback_negative', 'Bitte einen gültigen Preis eingeben.');
            return false;
        }

        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "INSERT INTO marketplace_listings
                    (user_id, category_id, listing_title, listing_description, listing_price, listing_creation_timestamp)
                VALUES
                    (:user_id, :category_id, :title, :description, :price, :created_at)";
        $query = $database->prepare($sql);
        $query->execute([
            ':user_id'     => Session::get('user_id'),
            ':category_id' => (int)$data['category_id'],
            ':title'       => trim($data['title']),
            ':description' => trim($data['description']),
            ':price'       => (float)$data['price'],
            ':created_at'  => time()
        ]);

        if ($query->rowCount() !== 1) {
            Session::add('feedback_negative', 'Angebot konnte nicht erstellt werden.');
            return false;
        }

        $listing_id = $database->lastInsertId();

        // Fotos hochladen, falls welche angegeben wurden
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
    public static function uploadPhotos($listing_id, $files)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $allowed_mimes = ['image/jpeg', 'image/png', 'image/gif'];
        $finfo = new finfo(FILEINFO_MIME_TYPE);

        // Ordner: /userpictures/marketplace/{listing_id}/
        $dir = Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$listing_id . '/';
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }


        // Bestehende Foto-Anzahl prüfen
        $existing = $database->prepare("SELECT COUNT(*) FROM marketplace_photos WHERE listing_id = :id");
        $existing->execute([':id' => (int)$listing_id]);
        $existingCount = (int)$existing->fetchColumn();

        $maxNew = 3 - $existingCount;
        if ($maxNew <= 0) {
            Session::add('feedback_negative', 'Bereits 3 Fotos vorhanden. Bitte erst ein Foto löschen.');
            return;
        }

        // Maximal 3 Fotos verarbeiten
        $count = min(count($files['name']), $maxNew);

        for ($i = 0; $i < $count; $i++) {
            // Leere oder fehlerhafte Uploads überspringen
            if ($files['error'][$i] !== UPLOAD_ERR_OK || empty($files['name'][$i])) {
                continue;
            }

            if ($files['size'][$i] > 5 * 1024 * 1024) {
                Session::add('feedback_negative', 'Foto ' . ($i + 1) . ' ist zu groß (max. 5 MB).');
                continue;
            }

            // MIME-Type aus Dateiinhalt prüfen (sicherer als Browser-Angabe)
            $mime = $finfo->file($files['tmp_name'][$i]);
            if (!in_array($mime, $allowed_mimes)) {
                Session::add('feedback_negative', 'Foto ' . ($i + 1) . ': Nur JPG, PNG und GIF erlaubt.');
                continue;
            }

            // Sicheren Dateinamen erzeugen
            $safeName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($files['name'][$i]));
            $storedName = time() . '_' . $listing_id . '_' . ($i + 1) . '_' . $safeName;

            if (!move_uploaded_file($files['tmp_name'][$i], $dir . $storedName)) {
                continue;
            }

            $sql = "INSERT INTO marketplace_photos (listing_id, photo_filename, photo_order)
                    VALUES (:listing_id, :filename, :order)";
            $query = $database->prepare($sql);
            $query->execute([
                ':listing_id' => (int)$listing_id,
                ':filename'   => $storedName,
                ':order'      => ($i + 1)
            ]);
        }
    }


    public static function getListingById($listing_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT l.listing_id, l.listing_title, l.listing_description, l.listing_price,
                    l.listing_creation_timestamp, l.user_id, l.category_id,
                    c.category_name, u.user_name
                FROM marketplace_listings l
                JOIN marketplace_categories c ON c.category_id = l.category_id
                JOIN users u ON u.user_id = l.user_id
                WHERE l.listing_id = :listing_id
                AND l.listing_active = 1
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([':listing_id' => (int)$listing_id]);

        return $query->fetch();
    }

    public static function getListingPhotos($listing_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT photo_id FROM marketplace_photos
                WHERE listing_id = :listing_id
                ORDER BY photo_order ASC";
        $query = $database->prepare($sql);
        $query->execute([':listing_id' => (int)$listing_id]);

        return $query->fetchAll();
    }


    /**
     * Gibt den absoluten Dateipfad eines Fotos zurück (für readfile()).
     * Gibt false zurück wenn das Foto nicht existiert.
     */
    public static function getPhotoPath($photo_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT listing_id, photo_filename
                FROM marketplace_photos
                WHERE photo_id = :photo_id
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([':photo_id' => (int)$photo_id]);
        $row = $query->fetch();

        if (!$row) return false;

        return Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$row->listing_id . '/' . $row->photo_filename;
    }


    public static function getListingInquiries($listing_id, $owner_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT c.id AS chat_id,
                    u.user_name AS buyer_name,
                    COUNT(CASE
                        WHEN m.sender_id != :owner_id
                            AND (cp_owner.last_read_at IS NULL OR m.created_at > cp_owner.last_read_at)
                        THEN 1
                    END) AS unread_count
                FROM chats c
                JOIN chat_participants cp_owner ON cp_owner.chat_id = c.id AND cp_owner.user_id = :owner_id
                JOIN chat_participants cp_buyer ON cp_buyer.chat_id = c.id AND cp_buyer.user_id != :owner_id
                JOIN users u ON u.user_id = cp_buyer.user_id
                LEFT JOIN messages m ON m.chat_id = c.id
                WHERE c.listing_id = :listing_id
                GROUP BY c.id, u.user_name
                ORDER BY unread_count DESC, c.id DESC";

        $query = $database->prepare($sql);
        $query->execute([
            ':listing_id' => (int)$listing_id,
            ':owner_id'   => (int)$owner_id,
        ]);

        return $query->fetchAll();
    }


    public static function deleteListing($listing_id, $owner_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "UPDATE marketplace_listings
                SET listing_active = 0
                WHERE listing_id = :listing_id AND user_id = :owner_id";
        $query = $database->prepare($sql);
        $query->execute([
            ':listing_id' => (int)$listing_id,
            ':owner_id'   => (int)$owner_id,
        ]);
        return $query->rowCount() === 1;
    }


    public static function markAsSold($listing_id, $owner_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();
        $sql = "UPDATE marketplace_listings
                SET listing_active = 0
                WHERE listing_id = :listing_id AND user_id = :owner_id";
        $query = $database->prepare($sql);
        $query->execute([':listing_id' => (int)$listing_id, ':owner_id' => (int)$owner_id]);
        return $query->rowCount() === 1;
    }



    public static function updateListing($listing_id, $owner_id, $data)
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

        $sql = "UPDATE marketplace_listings
                SET listing_title       = :title,
                    listing_description = :description,
                    listing_price       = :price,
                    category_id         = :category_id
                WHERE listing_id = :listing_id
                AND user_id    = :owner_id";
        $query = $database->prepare($sql);
        $query->execute([
            ':title'       => trim($data['title']),
            ':description' => trim($data['description']),
            ':price'       => (float)$data['price'],
            ':category_id' => (int)$data['category_id'],
            ':listing_id'  => (int)$listing_id,
            ':owner_id'    => (int)$owner_id,
        ]);

        // if ($query->rowCount() < 1) {
        //     Session::add('feedback_negative', 'Keine Änderungen gespeichert.');
        //     return false;
        // }

        Session::add('feedback_positive', 'Angebot erfolgreich aktualisiert.');
        return true;
    }


    public static function deletePhoto($photo_id, $owner_id)
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        // Sicherstellen, dass das Foto dem Owner gehört
        $sql = "SELECT p.listing_id, p.photo_filename
                FROM marketplace_photos p
                JOIN marketplace_listings l ON l.listing_id = p.listing_id
                WHERE p.photo_id = :photo_id AND l.user_id = :owner_id
                LIMIT 1";
        $query = $database->prepare($sql);
        $query->execute([':photo_id' => (int)$photo_id, ':owner_id' => (int)$owner_id]);
        $row = $query->fetch();

        if (!$row) return false;

        // Datei löschen
        $path = Config::get('PATH_USERPICTURES') . 'marketplace/' . (int)$row->listing_id . '/' . $row->photo_filename;
        if (file_exists($path)) {
            unlink($path);
        }

        // DB-Eintrag löschen
        $sql = "DELETE FROM marketplace_photos WHERE photo_id = :photo_id";
        $query = $database->prepare($sql);
        $query->execute([':photo_id' => (int)$photo_id]);

        return $query->rowCount() === 1;
    }
}



