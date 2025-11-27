<?php
// PHP built-in server router for Render/Koyeb
$filePath = __DIR__ . $_SERVER['REQUEST_URI'];

// If the file exists and is not a PHP file, serve it directly
if (file_exists($filePath) && is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) !== 'php') {
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'json' => 'application/json',
        'txt' => 'text/plain',
    ];
    
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    if (isset($mimeTypes[$extension])) {
        header('Content-Type: ' . $mimeTypes[$extension]);
    }
    
    readfile($filePath);
    exit;
}

// Otherwise, route through the main application
require_once 'index.php';
?>
