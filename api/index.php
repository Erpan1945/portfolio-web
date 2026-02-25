<?php
// Tampilkan error jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== CRITICAL: CORS & PREFLIGHT HANDLING =====
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, PATCH, OPTIONS, HEAD');
header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, X-CSRF-Token');
header('Access-Control-Max-Age: 86400');
header('Access-Control-Expose-Headers: Content-Length, X-JSON-Response-Count');

// Handle OPTIONS (preflight request)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    header('Content-Length: 0');
    exit();
}

// Set default content-type
header('Content-Type: application/json; charset=utf-8');

// Log request untuk debugging
error_log("API Request: {$_SERVER['REQUEST_METHOD']} {$_SERVER['REQUEST_URI']}", 0);

// 1. KEMBALIKAN CACHE INTI, TAPI JANGAN CACHE ROUTES!
$tmpSettings = [
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE' => '/tmp/config.php',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views'
];

foreach ($tmpSettings as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// 2. BUAT STRUKTUR FOLDER SEMENTARA
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 3. JALANKAN LARAVEL
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Laravel Bootstrap Error',
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
        'trace' => explode("\n", $e->getTraceAsString())
    ], JSON_PRETTY_PRINT);
    exit();
}