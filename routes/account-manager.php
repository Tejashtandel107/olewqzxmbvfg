<?php
use App\Http\Controllers\AccountManager\ProfileController;
use App\Http\Controllers\AccountManager\LineController;
use App\Http\Controllers\AccountManager\ClientController;
use App\Http\Controllers\AccountManager\CompanyController;
use App\Http\Controllers\AccountManager\OperatorController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','role:'.config("constant.ROLE_ACCOUNT_MANAGER_ID")])->namespace('App\Http\Controllers\AccountManager')->prefix('account-manager')->name('account-manager.')->group(function () {
    Route::prefix('reports')->group(function () {
        Route::get('lines', 'ReportLineController@view')->name('reports.lines');
    });
    Route::resource('line-expenses', 'LineExpenseMonthlyController')->only(['index']);
    Route::resource('line-incomes', 'LineIncomeMonthlyController')->only(['index']);
    
    Route::resource('lines', LineController::class)->only(['index','create','edit']);
    Route::resource('clients', ClientController::class)->only(['index','create','edit']);
    Route::resource('companies', CompanyController::class)->only(['index','create','edit']);
    Route::resource('operators', OperatorController::class)->only(['index']);

    Route::get('profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('profile',[ProfileController::class,'update'])->name('profile.update');
});

