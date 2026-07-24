<?php
/**
 * FlavorHub Autoloader (PSR-4 Compliant)
 * Automatically loads classes under the 'FlavorHub' namespace from the 'src/' directory.
 */
spl_autoload_register(function ($class) {
    $prefix = 'FlavorHub\\';
    $base_dir = __DIR__ . '/../src/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});
