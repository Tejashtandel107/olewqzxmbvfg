<?php
use App\Http\Controllers\Operator\ProfileController;
use App\Http\Controllers\Operator\LineController;
use App\Http\Controllers\Operator\LineExpenseMonthlyController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','role:'.config("constant.ROLE_OPERATOR_ID")])->namespace('App\Http\Controllers\Operator')->prefix('operator')->name('operator.')->group(function () {
    Route::prefix('reports')->group(function () {
        Route::get('lines', 'ReportLineController@view')->name('reports.lines');
    });

    Route::resource('line-expenses', LineExpenseMonthlyController::class)->only(['index']);
    Route::resource('lines', LineController::class)->only(['index','create','edit']);
    Route::get('profile',[ProfileController::class,'edit'])->name('profile.edit');
    Route::patch('profile',[ProfileController::class,'update'])->name('profile.update');
});

