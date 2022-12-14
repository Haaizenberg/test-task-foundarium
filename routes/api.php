<?php

use App\Http\Controllers\Api\CarController;
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


Route::group([
    'prefix' => '/car', 
], function () {
    Route::post('/book/{car}', [ CarController::class, 'book' ]);
    Route::post('/release/{car}', [ CarController::class, 'release' ]);
});