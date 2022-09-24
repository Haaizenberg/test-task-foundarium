<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\SuccessController;
use App\Http\Controllers\UserController;
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

Route::post('/login', [ApiTokenController::class, 'createToken']);
Route::middleware('auth:sanctum')->get('/private', [UserController::class, 'getUser']);
Route::get('/public', [SuccessController::class, 'success']);
