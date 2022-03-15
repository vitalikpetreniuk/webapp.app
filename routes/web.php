<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\RevenueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportingsController;
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

Route::controller(RevenueController::class)->group(function () {
    Route::post('/revenues/val', 'val')->name('revenues.val');
    Route::post('/revenues', 'store')->name('revenues.store');
});

Route::controller(UserController::class)->group(function () {
    Route::get('/register', 'create')->name('user.register-create');
    Route::post('/register', 'store')->name('user.register.store');

    Route::get('/login', 'userForm')->name('user.login-create');
    Route::post('/login', 'login')->name('user.login');
});

Route::controller(ExpenseController::class)->group(function () {
    Route::post('/expenses/val', 'val')->name('expenses.val');
    Route::post('/expenses', 'store')->name('expenses.store');
});

Route::controller(ReportingsController::class)->group(function () {
    Route::get('/reportings', 'index')->name('reportings.get');
});

Route::get('/special', function () {
   return view('special');
});
