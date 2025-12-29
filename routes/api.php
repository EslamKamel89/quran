<?php

use App\Http\Controllers\QuranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/quran')->group(function () {
    Route::get('/sura-index', [QuranController::class, 'suraIndex']);
    Route::get('/sura/{id}', [QuranController::class, 'getSuraById']);
    Route::get('/page/{page}/full', [QuranController::class, 'getFull']);
});
