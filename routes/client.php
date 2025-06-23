<?php

use App\Http\Controllers\Client\ProfileController;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

Route::middleware(['auth:sanctum','role:'.config("constant.ROLE_CLIENT_ID")])->namespace('App\Http\Controllers\Client')->prefix('client')->name('client.')->group(function () {
    Route::prefix('reports')->group(function () {
        Route::get('lines', 'ReportLineController@view')->name('reports.lines');
    });

    Route::resource('line-incomes', LineIncomeMonthlyController::class)->only(['index','show']);
    Route::resource('companies', CompanyController::class)->only(['index']);
    
    Route::get('profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('profile',[ProfileController::class,'update'])->name('profile.update');
});

