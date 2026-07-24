<?php
require_once __DIR__ . '/includes/header.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\SettingsDAO;
use FlavorHub\BusinessLogic\SettingsService;

$error = '';
$success = '';

try {
    $db = Database::getConnection();
    $settingsDAO = new SettingsDAO($db);
    $settingsService = new SettingsService($settingsDAO);
} catch (Exception $e) {
    $error = "Database Connection Error: " . $e->getMessage();
}

// Handle Form POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $siteName        = trim($_POST['site_name'] ?? '');
    $siteEmail       = trim($_POST['site_email'] ?? '');
    $siteDescription = trim($_POST['site_description'] ?? '');
    $itemsPerPage    = isset($_POST['items_per_page']) ? (int)$_POST['items_per_page'] : 10;

    try {
        if ($settingsService->saveSettings($siteName, $siteEmail, $siteDescription, $itemsPerPage)) {
            $success = "Site configuration updated successfully. Please refresh to see brand name changes.";
            // Reload settings
            $settings = $settingsService->getSettings();
        } else {
            $error = "Failed to update configuration.";
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

// Fetch current settings
$settings = [];
try {
    $settings = $settingsService->getSettings();
} catch (Exception $e) {
    $error = "Failed to load system settings: " . $e->getMessage();
}
?>

<!-- Alerts -->
<?php if ($error !== ''): ?>
  <div class="alert alert-danger alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<?php if ($success !== ''): ?>
  <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm py-3 mb-4" role="alert">
    <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
<?php endif; ?>

<div class="card border-0 rounded-4 shadow-sm p-4" style="max-width: 800px;">
  
  <div class="mb-4">
    <h4 class="fw-bold text-dark mb-1"><i class="bi bi-sliders text-primary me-2"></i>Site Settings</h4>
    <p class="text-secondary small mb-0">Configure global metadata, contact email addresses, and admin presentation variables.</p>
  </div>

  <form action="settings.php" method="POST">
    <div class="row g-4">
      
      <!-- Site Name -->
      <div class="col-12 col-md-6">
        <label for="site_name" class="form-label fw-semibold text-secondary">Site Title / Brand</label>
        <div class="input-group">
          <span class="input-group-text bg-light text-secondary"><i class="bi bi-egg-fried"></i></span>
          <input 
            type="text" 
            name="site_name" 
            id="site_name" 
            class="form-control rounded-end-3" 
            value="<?= htmlspecialchars($settings['site_name'] ?? 'FlavorHub') ?>"
            required
          >
        </div>
        <div class="form-text small mt-1">This sets the name shown in the sidebar header and top titles.</div>
      </div>

      <!-- Site Email -->
      <div class="col-12 col-md-6">
        <label for="site_email" class="form-label fw-semibold text-secondary">Administrative Email</label>
        <div class="input-group">
          <span class="input-group-text bg-light text-secondary"><i class="bi bi-envelope-at"></i></span>
          <input 
            type="email" 
            name="site_email" 
            id="site_email" 
            class="form-control rounded-end-3" 
            value="<?= htmlspecialchars($settings['site_email'] ?? 'admin@flavorhub.com') ?>"
            required
          >
        </div>
        <div class="form-text small mt-1">The primary system contact email address.</div>
      </div>

      <!-- Items per page -->
      <div class="col-12 col-md-6">
        <label for="items_per_page" class="form-label fw-semibold text-secondary">Pagination Size (Items Per Page)</label>
        <div class="input-group">
          <span class="input-group-text bg-light text-secondary"><i class="bi bi-list-ol"></i></span>
          <input 
            type="number" 
            name="items_per_page" 
            id="items_per_page" 
            class="form-control rounded-end-3" 
            value="<?= (int)($settings['items_per_page'] ?? 10) ?>"
            min="1" 
            max="100" 
            required
          >
        </div>
        <div class="form-text small mt-1">Limits the default listing size for resources (Max 100).</div>
      </div>

      <!-- Site Description -->
      <div class="col-12">
        <label for="site_description" class="form-label fw-semibold text-secondary">Footer / Meta Description</label>
        <textarea 
          name="site_description" 
          id="site_description" 
          rows="4" 
          class="form-control rounded-3"
          placeholder="Enter a brief summary of the site metadata..."
        ><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
        <div class="form-text small mt-1">A short introductory description summarizing your FlavorHub platform.</div>
      </div>

    </div>

    <div class="mt-4 pt-3 border-top text-end">
      <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold">Save Settings</button>
    </div>
  </form>

</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
