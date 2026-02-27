<?php
// 1. TANGANI CORS (Wajib untuk Vue Axios)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
    header('Access-Control-Allow-Headers: Content-Type, Accept, Authorization, X-Requested-With, Origin');
    exit(0);
}

// ====================================================================
// 2. KUNCI UTAMA: "MEMBOHONGI" LARAVEL AGAR TIDAK MEMOTONG /api
// ====================================================================
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';

// Paksa HTTPS mencegah redirect
$_SERVER['HTTPS'] = 'on';
$_SERVER['HTTP_X_FORWARDED_PROTO'] = 'https';

// Tampilkan error jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 3. CACHE INTI KE FOLDER SEMENTARA VERCEL
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

// 4. BUAT DIREKTORI SEMENTARA
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

// 5. BOOTING LARAVEL
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<h1>Fatal Boot Error</h1><p>" . $e->getMessage() . "</p>";
}