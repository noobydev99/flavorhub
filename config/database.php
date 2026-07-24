<?php
/**
 * FlavorHub Database Configuration
 * Returns database credentials and settings.
 */

return [
    'host'     => 'localhost',
    'dbname'   => 'my_project',
    'username' => 'root',
    'password' => '',
    'charset'  => 'utf8mb4',
    'options'  => [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]
];
