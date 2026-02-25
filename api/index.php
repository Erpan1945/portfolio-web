<?php
// Tampilkan error jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. HANYA SET FOLDER SEMENTARA YANG BENAR-BENAR PERLU
// (Kita HAPUS APP_ROUTES_CACHE dan APP_CONFIG_CACHE agar Laravel selalu baca kode terbaru!)
$tmpSettings = [
    'VIEW_COMPILED_PATH' => '/tmp/storage/framework/views'
];

foreach ($tmpSettings as $key => $value) {
    putenv("$key=$value");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

// 2. BUAT STRUKTUR FOLDER SEMENTARA DI MEMORI VERCEL
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs'
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 3. JALANKAN LARAVEL
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    echo "<div style='font-family: sans-serif; padding: 20px; background: #ffebee; color: #c62828; border-radius: 8px;'>";
    echo "<h1>🚨 Fatal Boot Error Terdeteksi</h1>";
    echo "<h2>Pesan Error:</h2>";
    echo "<p><strong>" . $e->getMessage() . "</strong></p>";
    echo "<h2>Lokasi File:</h2>";
    echo "<p>" . $e->getFile() . " pada baris " . $e->getLine() . "</p>";
    echo "<h2>Detail Trace:</h2>";
    echo "<pre style='background: #fff; padding: 15px; overflow-x: auto; border: 1px solid #ef9a9a;'>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}