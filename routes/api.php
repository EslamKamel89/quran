<?php

use App\Http\Controllers\QuranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/quran')->group(function () {
    Route::get('/sura-index', [QuranController::class, 'suraIndex']);
});
