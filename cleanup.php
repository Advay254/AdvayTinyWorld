<?php
require_once 'includes/config.php';

// Allow both web and CLI access
if (php_sapi_name() !== 'cli') {
    $api_key = $_SERVER['HTTP_X_API_KEY'] ?? '';
    if ($api_key !== ADMIN_PASSWORD) {
        http_response_code(401);
        die('Unauthorized');
    }
}

$db = getDB();
if (!$db) {
    die('Database connection failed');
}

try {
    // Delete locations older than 60 hours (2.5 days)
    $stmt = $db->prepare("DELETE FROM locations WHERE timestamp < datetime('now', '-60 hours')");
    $stmt->execute();
    $locations_count = $stmt->rowCount();

    // Deactivate expired items and delete their files
    $stmt = $db->prepare("SELECT slug, file_path FROM items WHERE expires_at < datetime('now') AND is_active = 1");
    $stmt->execute();
    $expired_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $files_deleted = 0;
    foreach ($expired_items as $item) {
        if ($item['file_path']) {
            $file_path = './' . ltrim($item['file_path'], '/');
            if (file_exists($file_path)) {
                unlink($file_path);
                $files_deleted++;
            }
        }
    }
    
    // Deactivate the expired items
    $stmt = $db->prepare("UPDATE items SET is_active = 0 WHERE expires_at < datetime('now') AND is_active = 1");
    $stmt->execute();
    $items_count = $stmt->rowCount();

    $message = "Cleanup completed: $locations_count locations deleted, $items_count items deactivated, $files_deleted files removed";
    
    if (php_sapi_name() === 'cli') {
        echo $message . "\n";
    } else {
        header('Content-Type: application/json');
        echo json_encode(['message' => $message]);
    }
    
} catch (Exception $e) {
    $error = "Cleanup error: " . $e->getMessage();
    error_log($error);
    
    if (php_sapi_name() === 'cli') {
        echo $error . "\n";
        exit(1);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => $error]);
    }
}
?>
