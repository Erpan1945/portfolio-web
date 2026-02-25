<?php
// Tampilkan error jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===== VERCEL-SPECIFIC: FIX REQUEST_URI & REQUEST_METHOD =====
// JANGAN set default REQUEST_METHOD ke GET! Parse dari berbagai source
if (!isset($_SERVER['REQUEST_METHOD']) || empty($_SERVER['REQUEST_METHOD'])) {
    // Try: X-HTTP-Method header
    $_SERVER['REQUEST_METHOD'] = $_SERVER['HTTP_X_HTTP_METHOD'] ?? 
                                 $_SERVER['HTTP_X_METHOD'] ?? 
                                 $_SERVER['REQUEST_METHOD'] ?? 
                                 'GET';
}

error_log("DEBUG REQUEST_METHOD sources: X-HTTP-Method=" . ($_SERVER['HTTP_X_HTTP_METHOD'] ?? 'null') . 
          " | REQUEST_METHOD=" . $_SERVER['REQUEST_METHOD'] . 
          " | HTTP_REQUEST_METHOD=" . ($_SERVER['HTTP_REQUEST_METHOD'] ?? 'null'), 0);

// Di Vercel, REQUEST_URI mungkin tidak diset dengan benar
// Ambil dari VERCEL_URL atau reconstruct dari path
if (empty($_SERVER['REQUEST_URI'])) {
    $path = $_SERVER['PATH'] ?? $_SERVER['PATH_INFO'] ?? '/';
    $query = !empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
    $_SERVER['REQUEST_URI'] = $path . $query;
}

// Pastikan ada leading slash di REQUEST_URI
if (strpos($_SERVER['REQUEST_URI'], '/') !== 0) {
    $_SERVER['REQUEST_URI'] = '/' . $_SERVER['REQUEST_URI'];
}

// Set SERVER_NAME jika tidak ada
if (empty($_SERVER['SERVER_NAME'])) {
    $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'] ?? 'localhost';
}

// Set SCRIPT_NAME untuk API endpoint
if (empty($_SERVER['SCRIPT_NAME'])) {
    $_SERVER['SCRIPT_NAME'] = '/api/index.php';
}

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
    '/tmp/storage/framework/cache',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}


// SIMPLE TEST ENDPOINT (tanpa perlu Laravel)
if ($_SERVER['REQUEST_URI'] === '/api/health' || $_SERVER['REQUEST_URI'] === '/health') {
    http_response_code(200);
    echo json_encode([
        'status' => 'ok',
        'request_method' => $_SERVER['REQUEST_METHOD'],
        'request_uri' => $_SERVER['REQUEST_URI'],
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit();
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