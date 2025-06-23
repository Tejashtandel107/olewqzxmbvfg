<?php

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
Route::middleware(['auth:sanctum'])->namespace('App\Http\Controllers\Api')->name('api.')->group(function () {
    Route::apiResource('operators', OperatorController::class)->except(['show']);
    Route::apiResource('account-managers', AccountManagerController::class)->except(['index']);
    Route::apiResource('clients', ClientController::class)->except(['index']);
    Route::apiResource('companies', CompanyController::class);
    Route::apiResource('lines', LineController::class)->except(['index','show']);
    //Route::apiResource('pricings', PricingController::class)->except(['show','index']);
});
