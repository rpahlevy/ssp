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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('/websites', App\Http\Controllers\Api\WebsiteController::class);
Route::apiResource('/users', App\Http\Controllers\Api\UserController::class);
Route::apiResource('/subscriptions', App\Http\Controllers\Api\SubscriptionController::class);
Route::apiResource('/articles', App\Http\Controllers\Api\ArticleController::class);
