<?php

use Illuminate\Support\Facades\Route;

// HAPUS atau KOMENTARI route default lama:
// Route::get('/', function () {
//     return view('welcome');
// });

// GANTI DENGAN INI (Catch-All Route):
// Artinya: "Tangkap SEMUA request ({any}) dan kembalikan ke view 'welcome'"
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');