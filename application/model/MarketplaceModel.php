<?php

class MarketplaceModel {
    
    /**
     * Gibt alle aktiven Listings zurück (neueste zuerst).
     * Lädt auch die ID des ersten Fotos für die Vorschau.
     */
    public static function getAllListings()
    {
        $database = DatabaseFactory::getFactory()->getConnection();

        $sql = "SELECT l.listing_id, l.listing_title, l.listing_price,
                       c.category_name, u.user_name,
                       (SELECT p.photo_id FROM marketplace_photos p
                        WHERE p.listing_id = l.listing_id ORDER BY p.photo_order ASC LIMIT 1) AS first_photo_id
                FROM marketplace_listings l
                JOIN marketplace_categories c ON c.category_id = l.category_id
                JOIN users u ON u.user_id = l.user_id
                WHERE l.listing_active = 1
                ORDER BY l.listing_creation_timestamp DESC";

        $query = $database->prepare($sql);
        $query->execute();

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
}
