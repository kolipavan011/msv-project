<?php

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

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('dashmin')->group(function () {
    Route::get('/{view?}', [ViewController::class, 'index'])->where('view', '(.*)')->name('dashmin');
});
