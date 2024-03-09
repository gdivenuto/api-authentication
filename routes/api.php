<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\GifController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['namespace' => 'Api', 'prefix' => 'v1'], function () {
    Route::post('/login', [AuthenticationController::class, 'store']);
    Route::post('/logout', [AuthenticationController::class, 'destroy'])->middleware('auth:api');
    Route::get('/gifs/search', [GifController::class, 'search'])->middleware('auth:api')->name('gifs.search');
    Route::get('/gifs/{id}', [GifController::class, 'getById'])->middleware('auth:api')->name('gifs.getById');
    Route::post('/favorite-gifs', [GifController::class, 'store'])->middleware('auth:api')->name('favoriteGifs.store');
});
