<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\RecipeDAO;

$recipeId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($recipeId <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Recipe ID is required.']);
    exit;
}

try {
    $db = Database::getConnection();
    $recipeDAO = new RecipeDAO($db);
    $recipe = $recipeDAO->findById($recipeId);
    
    if (!$recipe) {
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Recipe not found.']);
        exit;
    }
    
    echo json_encode($recipe);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
