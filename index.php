<?php
require_once __DIR__ . '/config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\UserDAO;
use FlavorHub\DataAccess\SettingsDAO;
use FlavorHub\BusinessLogic\AuthService;
use FlavorHub\BusinessLogic\SettingsService;

AuthService::startSession();

// Redirect to dashboard if already authenticated
if (isset($_SESSION['user_id'])) {
    header('Location: admin/dashboard.php');
    exit;
}

$error = $_GET['error'] ?? '';
$success = $_GET['success'] ?? '';
$siteName = "FlavorHub";

try {
    $db = Database::getConnection();
    $settingsDAO = new SettingsDAO($db);
    $settingsService = new SettingsService($settingsDAO);
    $settings = $settingsService->getSettings();
    $siteName = $settings['site_name'];
} catch (Exception $e) {
    // Graceful fallback if database connection is pending configuration
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || $password === '') {
        $error = 'Please fill in all fields.';
    } else {
        try {
            $db = Database::getConnection();
            $userDAO = new UserDAO($db);
            $authService = new AuthService($userDAO);

            if ($authService->login($email, $password)) {
                header('Location: admin/dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (Exception $e) {
            $error = 'Database Error: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - <?= htmlspecialchars($siteName) ?></title>
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Outfit', sans-serif;
      background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #31102f 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow-x: hidden;
      position: relative;
    }
    
    /* Decorative animated background bubbles */
    .bg-circle {
      position: absolute;
      border-radius: 50%;
      background: linear-gradient(45deg, rgba(249, 115, 22, 0.15), rgba(236, 72, 153, 0.15));
      filter: blur(80px);
      z-index: 0;
      animation: floatBubble 15s infinite alternate ease-in-out;
    }
    .circle-1 { width: 400px; height: 400px; top: -100px; left: -100px; }
    .circle-2 { width: 300px; height: 300px; bottom: -50px; right: -50px; animation-delay: -5s; }

    @keyframes floatBubble {
      0% { transform: translateY(0) scale(1); }
      100% { transform: translateY(30px) scale(1.1); }
    }

    .login-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 450px;
    }

    .glass-card {
      background: rgba(255, 255, 255, 0.03);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.08);
      border-radius: 24px;
      box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
      padding: 3rem 2.25rem;
      transition: transform 0.3s ease, border-color 0.3s ease;
    }

    .glass-card:hover {
      border-color: rgba(249, 115, 22, 0.3);
    }

    .form-control {
      background: rgba(255, 255, 255, 0.05);
      border: 1px solid rgba(255, 255, 255, 0.1);
      border-radius: 12px;
      padding: 0.75rem 1rem;
      color: #fff;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      background: rgba(255, 255, 255, 0.08);
      border-color: #f97316;
      box-shadow: 0 0 0 0.25rem rgba(249, 115, 22, 0.25);
      color: #fff;
    }

    .form-control::placeholder {
      color: rgba(255, 255, 255, 0.4);
    }

    .btn-submit {
      background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
      border: none;
      border-radius: 12px;
      padding: 0.75rem;
      font-weight: 600;
      color: #fff;
      transition: all 0.3s ease;
      box-shadow: 0 4px 15px rgba(249, 115, 22, 0.3);
    }

    .btn-submit:hover {
      background: linear-gradient(135deg, #fb923c 0%, #f97316 100%);
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(249, 115, 22, 0.45);
    }

    .btn-submit:active {
      transform: translateY(0);
    }

    .brand-logo {
      font-size: 3rem;
      background: linear-gradient(45deg, #f97316, #ec4899);
      background-clip: text;
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      margin-bottom: 0.5rem;
    }

    .form-label {
      font-weight: 500;
      color: rgba(255, 255, 255, 0.85);
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <div class="bg-circle circle-1"></div>
  <div class="bg-circle circle-2"></div>

  <div class="login-container px-3">
    <div class="glass-card text-white text-center">
      
      <div class="mb-4">
        <div class="brand-logo"><i class="bi bi-egg-fried"></i></div>
        <h2 class="fw-bold mb-1"><?= htmlspecialchars($siteName) ?></h2>
        <p class="text-white-50 small">Sign in to manage culinary flavors</p>
      </div>

      <?php if ($error !== ''): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-25 text-danger-emphasis rounded-3 text-start small mb-4 py-2.5 px-3" role="alert">
          <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
        </div>
      <?php endif; ?>

      <?php if ($success !== ''): ?>
        <div class="alert alert-success border-0 bg-success bg-opacity-25 text-success-emphasis rounded-3 text-start small mb-4 py-2.5 px-3" role="alert">
          <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
        </div>
      <?php endif; ?>

      <form action="index.php" method="POST" class="text-start">
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 border-secondary text-secondary" style="border-radius: 12px 0 0 12px;"><i class="bi bi-envelope"></i></span>
            <input 
              type="email" 
              name="email" 
              id="email" 
              class="form-control border-start-0 ps-0" 
              placeholder="admin@flavorhub.com" 
              style="border-radius: 0 12px 12px 0;"
              required
            >
          </div>
        </div>

        <div class="mb-4">
          <label for="password" class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text bg-transparent border-end-0 border-secondary text-secondary" style="border-radius: 12px 0 0 12px;"><i class="bi bi-lock"></i></span>
            <input 
              type="password" 
              name="password" 
              id="password" 
              class="form-control border-start-0 ps-0" 
              placeholder="••••••••" 
              style="border-radius: 0 12px 12px 0;"
              required
            >
          </div>
        </div>

        <button type="submit" class="btn btn-submit w-full w-100 mb-3">Sign In</button>
      </form>
    </div>
  </div>

  <!-- Bootstrap Bundle JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
