<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\LineExpenseMonthlyService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\LineIncomeMonthlyService;
use App\Models\LineIncomeMonthly;

use Helper;

class LineExpenseMonthlyController extends Controller
{
    public function __construct(Request $request)
    {
        $request->mergeIfMissing([
            'from' => Helper::DateFormat(Carbon::now()->firstOfMonth()->subMonth(),'m/Y'),
            'to' => Helper::DateFormat(Carbon::now()->firstOfMonth()->subMonth(),'m/Y')
        ]);
    }

    public function showAccountManagerReport(Request $request){
        $data = [
			'pagetitle'=>"Account Manager Production Report",
            "breadcrumbs"=>"",
        ];

        $account_managers = User::accountmanager()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        $lineExpensesMonthly = (new LineExpenseMonthlyService)->getAccountManagerExpenses($request->query())->groupBy('account_manager_id');

        return view('admin.report.expense.account-manager',array_merge($data,compact('request','lineExpensesMonthly','account_managers')));
    }

    public function showOperatorReport(Request $request){
        $data = [
			'pagetitle'=>"Operatore Production Report",
            "breadcrumbs"=>"",
        ];

        $operators = User::operator()->withTrashed()->get()->pluck('name','user_id')->toArray();
        $lineExpensesMonthly = (new LineExpenseMonthlyService)->getOperatorExpenses($request->query());

        return view('admin.report.expense.operator',array_merge($data,compact('request','lineExpensesMonthly','operators')));
    }
}
?>