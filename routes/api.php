<?php

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SourceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RevenueController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('expense/{expense}', [ExpenseController::class, 'getSingle']);
Route::post('expense/{expense}', [ExpenseController::class, 'update']);
Route::delete('expense/{expense}', [ExpenseController::class, 'delete']);
Route::post('expense', [ExpenseController::class, 'store']);
Route::get('/sources/', [SourceController::class, 'listSources']);


Route::get('revenue/{revenue}', [RevenueController::class, 'getSingle']);
Route::post('revenue/{revenue}', [RevenueController::class, 'update']);
Route::delete('revenue/{revenue}', [RevenueController::class, 'delete']);
