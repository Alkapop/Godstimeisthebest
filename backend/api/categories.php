<?php
/**
 * API Endpoint for Category Operations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../models/Category.php';

$category = new Category();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single category
            $result = $category->getById($_GET['id']);
            if ($result) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Category not found']);
            }
        } else {
            // Get all categories
            $result = $category->getAll();
            echo json_encode(['success' => true, 'data' => $result]);
        }
        break;
        
    case 'POST':
        // Create new category
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['name'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name is required']);
            break;
        }
        
        $result = $category->create($data);
        if ($result) {
            echo json_encode(['success' => true, 'id' => $result, 'message' => 'Category created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create category']);
        }
        break;
        
    case 'PUT':
        // Update category
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID is required']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $category->update($_GET['id'], $data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Category updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update category']);
        }
        break;
        
    case 'DELETE':
        // Delete category
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID is required']);
            break;
        }
        
        $result = $category->delete($_GET['id']);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Category deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete category']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>