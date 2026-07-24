<?php
require_once __DIR__ . '/includes/header.php';
?>

<!-- Page-specific styles -->
<link rel="stylesheet" href="income.css">





<?php
// Include the main content fragment from income.html (the content wrapper)
if (file_exists(__DIR__ . '/income.html')) {
  $html = file_get_contents(__DIR__ . '/income.html');

  // Extract the content section that starts with <div id="content"> and ends with <!-- Close content -->
  if (preg_match('/<div\s+id="content">([\s\S]*?)<\/div>\s*<!-- Close content -->/i', $html, $m)) {
    echo $m[1];
  } elseif (preg_match('/<div class="main-container">([\s\S]*?)<\/div>\s*<!-- Close main-container -->/i', $html, $m2)) {
    // fallback: extract the main-container
    echo $m2[1];
  } else {
    echo '<div class="card p-4">Unable to load Income content.</div>';
  }
} else {
  echo '<div class="card p-4">Income page content missing.</div>';
}

// Load Chart.js and the page's JS after content
?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="income.js?v=<?= time() ?>"></script>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
