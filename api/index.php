<?php
// Aktifkan error reporting secara manual untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Panggil index asli Laravel
require __DIR__ . '/../public/index.php';