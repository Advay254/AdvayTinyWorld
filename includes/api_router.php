<?php
// Set CORS headers for API
setCorsHeaders();

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$db = getDB();
if (!$db) {
    jsonResponse(['error' => 'Database connection failed'], 500);
}

$api_key = $_SERVER['HTTP_X_API_KEY'] ?? '';

// Get the endpoint from request
$request_uri = $_SERVER['REQUEST_URI'];
$endpoint = preg_replace('/^\/api\//', '', $request_uri);
$endpoint = explode('?', $endpoint)[0]; // Remove query string

// Verify API key for protected endpoints
function verifyApiKey() {
    global $api_key;
    if ($api_key !== ADMIN_PASSWORD) {
        jsonResponse(['error' => 'Invalid API key'], 401);
    }
}

switch($endpoint) {
    case 'locations/capture':
        handleLocationCapture($db);
        break;
    case 'locations/get':
        verifyApiKey();
        handleGetLocations($db);
        break;
    case 'items/create':
        verifyApiKey();
        handleCreateItem($db);
        break;
    case 'items/list':
        verifyApiKey();
        handleListItems($db);
        break;
    case 'items/update':
        verifyApiKey();
        handleUpdateItem($db);
        break;
    case 'items/delete':
        verifyApiKey();
        handleDeleteItem($db);
        break;
    case 'cleanup':
        verifyApiKey();
        handleCleanup($db);
        break;
    default:
        jsonResponse(['error' => 'Endpoint not found'], 404);
}

function handleLocationCapture($db) {
    $item_slug = sanitizeInput($_GET['item_slug'] ?? '');
    $lat = $_GET['lat'] ?? null;
    $long = $_GET['long'] ?? null;
    
    if (empty($item_slug)) {
        jsonResponse(['error' => 'Item slug required'], 400);
    }
    
    try {
        $stmt = $db->prepare("
            INSERT INTO locations (item_slug, latitude, longitude, ip_address, user_agent) 
            VALUES (?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            $item_slug,
            $lat ? (float)$lat : null,
            $long ? (float)$long : null,
            $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'Unknown',
            $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown'
        ]);
        
        jsonResponse(['success' => true]);
    } catch (Exception $e) {
        error_log("Location capture error: " . $e->getMessage());
        jsonResponse(['success' => true]); // Still return success to user
    }
}

// [Rest of the API functions remain the same as previous version]
// ... (include handleGetLocations, handleCreateItem, handleListItems, handleUpdateItem, handleDeleteItem, handleCleanup)
?>
