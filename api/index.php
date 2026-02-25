<?php
// Paksa PHP menampilkan semua error
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    // Panggil inti aplikasi Laravel
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    // Jika Laravel crash saat booting, tangkap dan tampilkan di layar
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