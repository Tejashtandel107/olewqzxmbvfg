<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LineExpenseMonthlyService;
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

    public function index(Request $request){
        $data = [
			'pagetitle'=>"Production Report",
            "breadcrumbs"=>"",
        ];
        $request->merge(['user_id' => Auth::id()]);
        $lineExpensesMonthly = (new LineExpenseMonthlyService)->getOperatorExpenses($request->query());

        return view('operator.report.expense.view',array_merge($data,compact('request','lineExpensesMonthly')));
    }
}
?>