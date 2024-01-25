<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::middleware('auth:sanctum')->group(function(){
    Route::group(['prefix' => '/user'], function() {
        Route::get('/', [\App\Http\Controllers\UserController::class, 'info']);
        Route::get('/list', [\App\Http\Controllers\UserController::class, 'list']);
    });
    Route::group(['prefix' => '/todo'], function() {
        Route::post('/get', [\App\Http\Controllers\TodoController::class, 'get']);
        Route::post('/create', [\App\Http\Controllers\TodoController::class, 'create']);
        Route::post('/remove', [\App\Http\Controllers\TodoController::class, 'remove']);
    });
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
