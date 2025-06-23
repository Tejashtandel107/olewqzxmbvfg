<?php

use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\LineIncomeMonthlyController;
use App\Http\Controllers\Admin\LineIncomeMonthlyInvoiceController;
use App\Http\Controllers\Admin\LineIncomeStatusHistoryController;
use App\Http\Controllers\Admin\LineExpenseMonthlyController;
use App\Http\Controllers\Admin\ReportLineController;
use App\Http\Controllers\Admin\LineController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\ImportCompanyController;
use App\Http\Controllers\Admin\AccountManagerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum', 'role:' . config("constant.ROLE_SUPER_ADMIN_ID")])->namespace('App\Http\Controllers\Admin')->prefix('admin')->name('admin.')->group(function () {
    Route::prefix('line-expenses')->group(function () {
        Route::get('operators', [LineExpenseMonthlyController::class, 'showOperatorReport'])->name('line-expenses.operators');
        Route::get('acccount-managers', [LineExpenseMonthlyController::class, 'showAccountManagerReport'])->name('line-expenses.account-managers');
    });

    Route::prefix('reports/lines')->group(function () {
        Route::get('client', [ReportLineController::class, 'showClientReport'])->name('reports.lines.client');
        Route::get('operator', [ReportLineController::class, 'showOperatorReport'])->name('reports.lines.operator');
        Route::get('team', [ReportLineController::class, 'showAccountManagerReport'])->name('reports.lines.team');
    });

    Route::resource('lines', LineController::class)->only(['index', 'create', 'edit']);
    Route::resource('clients', ClientController::class)->only(['index', 'create', 'edit']);
    Route::resource('account-managers', AccountManagerController::class)->only(['index', 'create', 'edit']);
    Route::resource('operators', OperatorController::class)->only(['index', 'create', 'edit']);
    Route::resource('companies', CompanyController::class)->only(['index', 'create', 'edit']);
    Route::resource('imports', ImportCompanyController::class)->only(['create', 'store']);
    Route::resource('line-incomes', LineIncomeMonthlyController::class)->only(['index','show']);
    Route::post('line-incomes/download', [LineIncomeMonthlyController::class, 'download'])->name('line-incomes.download');
    Route::post('line-incomes/bulk-update', [LineIncomeMonthlyController::class, 'bulkUpdate'])->name('line-incomes.bulk.update');
    Route::resource('line-incomes.statuses', LineIncomeStatusHistoryController::class)->only(['index']);
    
    //Route::resource('clients.invoices', LineIncomeMonthlyInvoiceController::class)->only(['show']);
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('profile-settings', [ProfileController::class, 'updateSettings'])->name('profile.update-settings');
});
