<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
});

Route::post('/revenues/val', [\App\Http\Controllers\RevenueController::class, 'val'])->name('revenues.val');
Route::post('/revenues', [\App\Http\Controllers\RevenueController::class, 'store'])->name('revenues.store');

Route::controller(UserController::class)->group(function () {
    Route::get('/register', 'create')->name('user.register-create');
    Route::post('/register', 'store')->name('user.register.store');

    Route::get('/login', 'userForm')->name('user.login-create');
    Route::post('/login', 'login')->name('user.login');
});

Route::get('/special', function () {
   return view('special');
});
