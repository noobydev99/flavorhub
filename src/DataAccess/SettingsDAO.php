<?php
namespace FlavorHub\DataAccess;

use PDO;

/**
 * Settings Data Access Object (DataAccess Layer)
 * Implements SQL operations for the settings table.
 */
class SettingsDAO {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Fetch the single site settings row.
     */
    public function getSettings(): array {
        $stmt = $this->db->query("SELECT * FROM settings WHERE id = 1 LIMIT 1");
        $settings = $stmt->fetch();
        
        // Return default values if table is empty
        if (!$settings) {
            return [
                'id'               => 1,
                'site_name'        => 'FlavorHub',
                'site_email'       => 'admin@flavorhub.com',
                'site_description' => 'Culinary Dashboard',
                'items_per_page'   => 10
            ];
        }
        return $settings;
    }

    /**
     * Update the site settings.
     */
    public function updateSettings(
        string $siteName, 
        string $siteEmail, 
        ?string $siteDescription, 
        int $itemsPerPage
    ): bool {
        $sql = "INSERT INTO settings (id, site_name, site_email, site_description, items_per_page) 
                VALUES (1, :site_name, :site_email, :site_description, :items_per_page)
                ON DUPLICATE KEY UPDATE 
                    site_name = VALUES(site_name), 
                    site_email = VALUES(site_email), 
                    site_description = VALUES(site_description), 
                    items_per_page = VALUES(items_per_page)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'site_name'        => $siteName,
            'site_email'       => $siteEmail,
            'site_description' => $siteDescription,
            'items_per_page'   => $itemsPerPage
        ]);
    }
}
