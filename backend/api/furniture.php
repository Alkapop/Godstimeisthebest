<?php
/**
 * API Endpoint for Furniture Operations
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

require_once '../models/Furniture.php';

$furniture = new Furniture();
$method = $_SERVER['REQUEST_METHOD'];

switch($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single furniture item
            $result = $furniture->getById($_GET['id']);
            if ($result) {
                echo json_encode(['success' => true, 'data' => $result]);
            } else {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Furniture item not found']);
            }
        } else {
            // Get all furniture items with optional filters
            $category_id = isset($_GET['category_id']) ? $_GET['category_id'] : null;
            $featured_only = isset($_GET['featured']) && $_GET['featured'] == 'true';
            $spotlight_only = isset($_GET['spotlight']) && $_GET['spotlight'] == 'true';
            
            $result = $furniture->getAll($category_id, $featured_only, $spotlight_only);
            echo json_encode(['success' => true, 'data' => $result]);
        }
        break;
        
    case 'POST':
        // Create new furniture item
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!$data || !isset($data['name']) || !isset($data['category_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Name and category_id are required']);
            break;
        }
        
        $result = $furniture->create($data);
        if ($result) {
            echo json_encode(['success' => true, 'id' => $result, 'message' => 'Furniture item created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to create furniture item']);
        }
        break;
        
    case 'PUT':
        // Update furniture item
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID is required']);
            break;
        }
        
        $data = json_decode(file_get_contents('php://input'), true);
        $result = $furniture->update($_GET['id'], $data);
        
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Furniture item updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to update furniture item']);
        }
        break;
        
    case 'DELETE':
        // Delete furniture item
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID is required']);
            break;
        }
        
        $result = $furniture->delete($_GET['id']);
        if ($result) {
            echo json_encode(['success' => true, 'message' => 'Furniture item deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to delete furniture item']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        break;
}
?>