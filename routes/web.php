<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\FolderController;
use App\Http\Controllers\Admin\ViewController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\Authenticate;
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

//Auth Routes
Route::get('/dashmin-login', [AuthController::class, 'show'])->name('login');
Route::post('/dashmin-login', [AuthController::class, 'login']);
Route::get('/dashmin-logout', [AuthController::class, 'logout'])->name('logout');
Route::get('dashmin/{view?}', [ViewController::class, 'index'])->middleware(Authenticate::class)->where('view', '(.*)')->name('dashmin');

Route::prefix('api')->middleware(Authenticate::class)->group(function () {

    //Dashborad route
    Route::get('dashboard', [DashboardController::class, 'index']);

    // Uploads Route
    Route::prefix('upload')->group(function () {
        Route::post('/{id}', [UploadController::class, 'store']);
        Route::delete('/{id}', [UploadController::class, 'destroy']);
    });

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
        Route::post('/attach/{id}', [PostController::class, 'attach']);
        Route::post('/{id}', [PostController::class, 'store']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    // Posts Route
    Route::prefix('videos')->group(function () {
        Route::get('/{id}', [VideoController::class, 'index']);
        Route::post('/create', [VideoController::class, 'create']);
        Route::post('/paste/{id}', [VideoController::class, 'paste']);
        Route::post('/{id}', [VideoController::class, 'store']);
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
