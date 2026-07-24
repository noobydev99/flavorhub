<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../config/autoload.php';

use FlavorHub\DataAccess\Database;
use FlavorHub\DataAccess\RecipeDAO;

try {
    $db = Database::getConnection();
    $recipeDAO = new RecipeDAO($db);
    
    $category = trim($_GET['category'] ?? '');
    
    // Get all recipes or filter by category
    $recipes = $recipeDAO->getAll();
    
    // Filter by category if provided
    if ($category !== '') {
        $recipes = array_filter($recipes, function($recipe) use ($category) {
            return strtolower($recipe['category_name']) === strtolower($category);
        });
    }
    
    // Reindex array to remove gaps from filtering
    $recipes = array_values($recipes);
    
    echo json_encode($recipes);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
