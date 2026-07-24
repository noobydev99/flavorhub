<?php
require_once __DIR__ . '/../../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\SettingsDAO;
use FlavorHub\BusinessLogic\SettingsService;
use FlavorHub\BusinessLogic\AuthService;
use FlavorHub\DataAccess\UserDAO;

$authService = new AuthService(new UserDAO(Database::getConnection()));
$authService->checkAuth();

$siteName = 'FlavorHub';
try {
    $db          = Database::getConnection();
    $settings    = (new SettingsService(new SettingsDAO($db)))->getSettings();
    $siteName    = $settings['site_name'];
} catch (Exception $e) {}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($siteName) ?> — Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    /* ─── Design Tokens ─────────────────────────────── */
    :root {
      --sidebar-w: 256px;
      --brand:     #f97316;
      --brand-dk:  #ea580c;
      --indigo:    #6366f1;
      --slate-50:  #f8fafc;
      --slate-100: #f1f5f9;
      --slate-200: #e2e8f0;
      --slate-300: #cbd5e1;
      --slate-500: #64748b;
      --slate-600: #475569;
      --slate-700: #334155;
      --slate-900: #0f172a;
      --sidebar-bg:#0f172a;
      --radius-xl: 16px;
      --radius-2xl:20px;
      --shadow-sm: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
      --shadow-md: 0 4px 12px rgba(0,0,0,.08);
      --shadow-lg: 0 10px 30px rgba(0,0,0,.10);
      --transition: all .2s ease;
    }

    /* ─── Reset / Base ──────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    body {
      font-family: 'Outfit', sans-serif;
      background-color: var(--slate-100);
      color: var(--slate-700);
      margin: 0; overflow-x: hidden;
    }

    /* ─── Layout Shell ──────────────────────────────── */
    .app-wrapper { display: flex; min-height: 100vh; }
    #content     { flex: 1; display: flex; flex-direction: column; min-width: 0; margin-left: var(--sidebar-w); transition: margin-left .3s ease; }
    .main-container { flex: 1; padding: 2rem 1.75rem; }

    /* ─── Sidebar ───────────────────────────────────── */
    #sidebar {
      position: fixed;
      top: 0;
      bottom: 0;
      left: 0;
      width: var(--sidebar-w);
      min-width: var(--sidebar-w);
      background: var(--sidebar-bg);
      display: flex;
      flex-direction: column;
      transition: margin-left .3s ease;
      z-index: 1000;
      overflow-y: auto;
    }
    #sidebar.collapsed { margin-left: calc(-1 * var(--sidebar-w)); }
    #sidebar.collapsed ~ #content { margin-left: 0; }

    .sidebar-header {
      padding: 1.5rem 1.25rem 1.25rem;
      border-bottom: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-brand {
      font-size: 1.35rem;
      font-weight: 800;
      background: linear-gradient(135deg, #f97316, #ec4899);
      -webkit-background-clip: text;
      background-clip: text;
      -webkit-text-fill-color: transparent;
      display: flex; align-items: center; gap: .5rem;
      text-decoration: none;
    }
    .sidebar-brand i { -webkit-text-fill-color: #fbbf24; }

    .sidebar-section-label {
      padding: .75rem 1.25rem .35rem;
      font-size: .68rem;
      font-weight: 700;
      letter-spacing: 1px;
      color: rgba(255,255,255,.25);
      text-transform: uppercase;
    }

    .sidebar-nav { list-style: none; padding: .5rem .75rem; margin: 0; flex: 1; }
    .sidebar-nav li { margin-bottom: 2px; }
    .sidebar-nav a {
      display: flex; align-items: center; gap: .75rem;
      padding: .65rem 1rem;
      color: rgba(255,255,255,.55);
      text-decoration: none;
      border-radius: 10px;
      font-size: .9rem;
      font-weight: 500;
      transition: var(--transition);
    }
    .sidebar-nav a i { font-size: 1rem; width: 20px; text-align: center; }
    .sidebar-nav a:hover  { background: rgba(255,255,255,.06); color: rgba(255,255,255,.9); }
    .sidebar-nav li.active a {
      background: linear-gradient(135deg, rgba(249,115,22,.18), rgba(236,72,153,.08));
      color: #fb923c;
      font-weight: 600;
      border-left: 3px solid #f97316;
      border-radius: 0 10px 10px 0;
      padding-left: calc(1rem - 3px);
    }

    .sidebar-footer {
      padding: .75rem;
      border-top: 1px solid rgba(255,255,255,.06);
    }
    .sidebar-footer a {
      display: flex; align-items: center; gap: .75rem;
      padding: .65rem 1rem;
      color: rgba(255,255,255,.4);
      text-decoration: none;
      border-radius: 10px;
      font-size: .9rem;
      font-weight: 500;
      transition: var(--transition);
    }
    .sidebar-footer a:hover { background: rgba(239,68,68,.1); color: #f87171; }

    /* ─── Top Navbar ────────────────────────────────── */
    .top-navbar {
      background: #ffffff;
      border-bottom: 1px solid var(--slate-200);
      padding: .875rem 1.75rem;
      display: flex; align-items: center;
      gap: 1rem;
      position: sticky; top: 0; z-index: 900;
      box-shadow: var(--shadow-sm);
    }
    .page-title {
      font-size: 1rem;
      font-weight: 700;
      color: var(--slate-900);
    }
    .sidebar-toggle {
      background: none; border: 1px solid var(--slate-200);
      border-radius: 10px; padding: .4rem .6rem;
      color: var(--slate-600); cursor: pointer;
      transition: var(--transition);
      line-height: 1;
    }
    .sidebar-toggle:hover { background: var(--slate-100); border-color: var(--slate-300); }

    /* user avatar pill */
    .user-pill {
      display: flex; align-items: center; gap: .5rem;
      background: var(--slate-100);
      border: 1px solid var(--slate-200);
      border-radius: 999px;
      padding: .3rem .75rem .3rem .3rem;
      cursor: pointer;
      transition: var(--transition);
      text-decoration: none;
    }
    .user-pill:hover { border-color: var(--slate-300); background: var(--slate-200); }
    .user-avatar {
      width: 30px; height: 30px;
      background: linear-gradient(135deg, #f97316, #ec4899);
      color: #fff; border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-weight: 700; font-size: .8rem;
    }
    .user-name { font-size: .85rem; font-weight: 600; color: var(--slate-700); }

    /* ─── Cards ─────────────────────────────────────── */
    .card, .fh-card {
      background: #ffffff !important;
      border: 1px solid var(--slate-200) !important;
      border-radius: var(--radius-2xl) !important;
      box-shadow: var(--shadow-sm) !important;
      transition: box-shadow .25s ease, border-color .25s ease !important;
    }
    .card:hover, .fh-card:hover {
      box-shadow: var(--shadow-md) !important;
      border-color: var(--slate-300) !important;
    }
    .card-header {
      background: #ffffff !important;
      border-bottom: 1px solid var(--slate-200) !important;
      border-radius: var(--radius-2xl) var(--radius-2xl) 0 0 !important;
    }

    /* ─── Stat Cards ─────────────────────────────────── */
    .stat-card { position: relative; overflow: hidden; padding: 1.5rem; }
    .stat-icon {
      width: 52px; height: 52px;
      border-radius: 14px;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.35rem;
      flex-shrink: 0;
    }
    .stat-label {
      font-size: .72rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .8px;
      color: var(--slate-500); margin-bottom: .25rem;
    }
    .stat-value { font-size: 2rem; font-weight: 800; color: var(--slate-900); line-height: 1; }
    .stat-ghost {
      position: absolute; right: -8px; bottom: -12px;
      font-size: 5.5rem; opacity: .06; pointer-events: none; line-height: 1;
      color: var(--slate-900);
    }
    .stat-link { font-size: .78rem; font-weight: 600; text-decoration: none; margin-top: .75rem; display: inline-flex; align-items: center; gap: .25rem; }

    /* icon tints */
    .icon-indigo { background: rgba(99,102,241,.1); color: #6366f1; }
    .icon-amber  { background: rgba(245,158,11,.1); color: #f59e0b; }
    .icon-emerald{ background: rgba(16,185,129,.1); color: #10b981; }
    .icon-rose   { background: rgba(244,63,94,.1);  color: #f43f5e; }

    /* ─── Tables ─────────────────────────────────────── */
    .fh-table { width: 100%; font-size: .875rem; border-collapse: collapse; }
    .fh-table thead th {
      padding: .75rem 1rem;
      font-size: .72rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .8px;
      color: var(--slate-500);
      border-bottom: 1px solid var(--slate-200);
      white-space: nowrap;
    }
    .fh-table tbody tr { border-bottom: 1px solid rgba(226,232,240,.5); transition: background .15s; }
    .fh-table tbody tr:last-child { border-bottom: none; }
    .fh-table tbody tr:hover { background: var(--slate-50); }
    .fh-table tbody td { padding: .875rem 1rem; color: var(--slate-700); vertical-align: middle; }

    /* ─── Status Badges ─────────────────────────────── */
    .badge-pill {
      display: inline-flex; align-items: center; gap: .35rem;
      padding: .25rem .65rem; border-radius: 999px;
      font-size: .72rem; font-weight: 600; border: 1px solid transparent;
    }
    .badge-pill .dot { width: 6px; height: 6px; border-radius: 50%; }
    .badge-approved { background: rgba(16,185,129,.1); color: #059669; border-color: rgba(16,185,129,.25); }
    .badge-approved .dot { background: #10b981; }
    .badge-pending  { background: rgba(245,158,11,.1); color: #b45309; border-color: rgba(245,158,11,.25); }
    .badge-pending .dot  { background: #f59e0b; animation: pulse 1.5s infinite; }
    .badge-active   { background: rgba(99,102,241,.1); color: #4338ca; border-color: rgba(99,102,241,.25); }
    .badge-active .dot   { background: #6366f1; }

    @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:.4} }

    /* ─── Buttons ───────────────────────────────────── */
    .btn { font-family: 'Outfit', sans-serif; font-weight: 600; border-radius: 10px !important; }
    .btn-brand {
      background: linear-gradient(135deg, #f97316, #ea580c);
      color: #fff; border: none;
      box-shadow: 0 4px 14px rgba(249,115,22,.3);
      transition: var(--transition);
    }
    .btn-brand:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(249,115,22,.4); color: #fff; }

    /* ─── Forms ─────────────────────────────────────── */
    .form-control, .form-select {
      border: 1px solid var(--slate-200) !important;
      border-radius: 10px !important;
      font-family: 'Outfit', sans-serif;
      font-size: .9rem;
      color: var(--slate-700);
      background: #fff;
      transition: var(--transition);
    }
    .form-control:focus, .form-select:focus {
      border-color: #f97316 !important;
      box-shadow: 0 0 0 3px rgba(249,115,22,.15) !important;
    }
    .form-label { font-weight: 600; font-size: .85rem; color: var(--slate-600); margin-bottom: .4rem; }

    /* ─── Alerts ────────────────────────────────────── */
    .fh-alert {
      padding: .875rem 1.1rem;
      border-radius: 12px;
      font-size: .875rem;
      font-weight: 500;
      display: flex; align-items: flex-start; gap: .6rem;
      border: 1px solid transparent;
      margin-bottom: 1.5rem;
    }
    .fh-alert-danger  { background: rgba(239,68,68,.06); border-color: rgba(239,68,68,.2); color: #b91c1c; }
    .fh-alert-success { background: rgba(16,185,129,.06); border-color: rgba(16,185,129,.2); color: #047857; }

    /* ─── Comment cards ─────────────────────────────── */
    .msg-card {
      background: var(--slate-50);
      border: 1px solid var(--slate-200);
      border-radius: 14px;
      padding: .875rem 1rem;
      transition: border-color .2s;
    }
    .msg-card:hover { border-color: var(--slate-300); }

    /* ─── Page section header ───────────────────────── */
    .section-header {
      display: flex; align-items: center; justify-content: space-between;
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--slate-200);
      margin-bottom: 1.5rem;
    }
    .section-title { font-size: 1rem; font-weight: 700; color: var(--slate-900); margin: 0; }

    /* ─── Responsive ─────────────────────────────────── */
    @media (max-width: 768px) {
      #sidebar { margin-left: calc(-1 * var(--sidebar-w)); }
      #sidebar.active { margin-left: 0; }
      #content { margin-left: 0; }
      .main-container { padding: 1.25rem 1rem; }
    }
  </style>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
<div class="app-wrapper">
<?php
require_once __DIR__ . '/sidebar.php';
require_once __DIR__ . '/navbar.php';
?>

