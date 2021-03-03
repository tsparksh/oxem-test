<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\GoodsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::apiResource('goods', GoodsController::class)->except('update');

Route::get('categories/{id}/goods', [CategoryController::class, 'goods']);
Route::apiResource('categories', CategoryController::class)->except('show');
