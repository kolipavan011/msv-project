<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\ViewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'welcome');

Route::prefix('api')->group(function () {
    // Users Route
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index']);
        Route::post('/create', [UserController::class, 'create']);
        Route::post('/{id}', [UserController::class, 'store']);
        Route::delete('/{id}', [UserController::class, 'destroy']);
    });

    // Posts Route
    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::post('/create', [PostController::class, 'create']);
        Route::post('/{id}', [PostController::class, 'store']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    // Posts Route
    Route::prefix('videos')->group(function () {
        Route::get('/', [VideoController::class, 'index']);
        Route::post('/create', [VideoController::class, 'create']);
        Route::post('/{id}', [VideoController::class, 'update']);
        Route::delete('/{id}', [VideoController::class, 'destroy']);
    });

    // Posts Route
    Route::prefix('folders')->group(function () {
        Route::get('/', [FolderController::class, 'index']);
        Route::post('/create', [FolderController::class, 'create']);
        Route::post('/{id}', [FolderController::class, 'update']);
        Route::delete('/{id}', [FolderController::class, 'destroy']);
    });
});

Route::prefix('dashmin')->group(function () {
    Route::get('/{view?}', [ViewController::class, 'index'])->where('view', '(.*)')->name('dashmin');
});
