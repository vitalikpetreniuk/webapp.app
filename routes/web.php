<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\SourceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AnalyticsController;
use App\Http\Middleware\Authenticate;
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

Route::controller(HomeController::class)->middleware(Authenticate::class)->group(function () {
    Route::get('/', 'index')->name('home');
});
//
Route::controller(RevenueController::class)->middleware(Authenticate::class)->group(function () {
    Route::post('/revenues/val', 'val')->name('revenues.val');
    Route::post('/revenues', 'store')->name('revenues.store');
});

Route::controller(UserController::class)->group(function () {
//    Route::get('/register', 'create')->name('user.register-create');
//    Route::post('/register', 'store')->name('user.register.store');

    Route::get('/login', 'userForm')->name('user.login-create');
    Route::post('/login', 'login')->name('user.login');
});

Route::controller(ExpenseController::class)->middleware(Authenticate::class)->group(function () {
    Route::post('/expenses/val', 'val')->name('expenses.val');
    Route::post('/expenses', 'store')->name('expenses.store');
});

Route::controller(AnalyticsController::class)->middleware(Authenticate::class)->group(function () {
    Route::get('/analytics', 'index')->name('analytics');
});

Route::get('/sweetspot', function () {
   return view('reportings/sweetspot');
})->middleware(Authenticate::class)->name('sweetspot');

Route::get('/special', function () {
   return view('reportings/special');
})->middleware(Authenticate::class)->name('special');
