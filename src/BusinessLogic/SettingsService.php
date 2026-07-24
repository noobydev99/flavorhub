<?php
namespace FlavorHub\BusinessLogic;

use FlavorHub\DataAccess\SettingsDAO;
use Exception;

/**
 * Settings Service (Business Logic Layer)
 * Validates and updates application-wide settings.
 */
class SettingsService {
    private SettingsDAO $settingsDAO;

    public function __construct(SettingsDAO $settingsDAO) {
        $this->settingsDAO = $settingsDAO;
    }

    /**
     * Get site settings.
     */
    public function getSettings(): array {
        return $this->settingsDAO->getSettings();
    }

    /**
     * Save site settings with validation checks.
     */
    public function saveSettings(string $siteName, string $siteEmail, ?string $siteDescription, int $itemsPerPage): bool {
        $siteName  = trim($siteName);
        $siteEmail = trim($siteEmail);

        if ($siteName === '') {
            throw new Exception("Site Name is a required field.");
        }

        if (!filter_var($siteEmail, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Please provide a valid administrative email address.");
        }

        if ($itemsPerPage <= 0 || $itemsPerPage > 100) {
            throw new Exception("Items per page must be a positive number between 1 and 100.");
        }

        return $this->settingsDAO->updateSettings($siteName, $siteEmail, $siteDescription, $itemsPerPage);
    }
}
