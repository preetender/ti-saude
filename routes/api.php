<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CollectionController;
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

Route::prefix('v1')
    ->middleware('auth:api')
    ->group(function () {
        Route::get('account', fn (Request $request) => $request->user());

        Route::prefix('{model}')
            ->group(function () {
                Route::get('/', [CollectionController::class, 'index']);
                Route::get('{id}', [CollectionController::class, 'show']);
                Route::post('/', [CollectionController::class, 'store']);
                Route::put('{id}', [CollectionController::class, 'update']);
                Route::delete('{id}', [CollectionController::class, 'destroy']);
            });
    });
