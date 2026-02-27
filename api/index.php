<?php
// 1. TANGANI CORS & PREFLIGHT OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Origin');
    exit(0);
}

// 2. PAKSA HTTPS (Mencegah Redirect 301)
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

// ==========================================
// 3. INI KUNCI RAHASIANYA: KEMBALIKAN PREFIX /api
// ==========================================
$uri = $_SERVER['REQUEST_URI'] ?? '';
// Jika Vercel memotong '/api', kita pasang kembali secara manual!
if ($uri !== '' && strpos($uri, '/api') !== 0) {
    $_SERVER['REQUEST_URI'] = '/api' . $uri;
}

// Tampilkan error jika ada masalah server
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 4. CACHE INTI KE FOLDER SEMENTARA (/tmp) VERCEL
$tmpSettings = [
    'APP_SERVICES_CACHE' => '/tmp/services.php',
    'APP_PACKAGES_CACHE' => '/tmp/packages.php',
    'APP_CONFIG_CACHE'   => '/tmp/config.php',
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views'
];

foreach ($tmpSettings as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// 5. BUAT DIREKTORI SEMENTARA
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 6. BOOTING LARAVEL
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Fatal Boot Error</h1><p>" . $e->getMessage() . "</p>";
}