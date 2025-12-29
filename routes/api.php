<?php

use App\Http\Controllers\QuranController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::prefix('/quran')->group(function () {
    Route::get('/sura-index', [QuranController::class, 'suraIndex']);
    Route::get('/sura/{id}', [QuranController::class, 'getSuraById']);
    Route::get('/page/{page}/full', [QuranController::class, 'getFull']);
    Route::get('/sura/page/{suraId}', [QuranController::class, 'getSuraByPage']);
    Route::get('/page/{page}/ayat-sura', [QuranController::class, 'pageAyatSura']);
    Route::get('/juz/{sura}/{aya}', [QuranController::class, 'getJuz']);
    Route::get('/search/{key}', [QuranController::class, 'searchByWord']);
});
