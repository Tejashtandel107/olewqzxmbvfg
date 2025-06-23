<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\LineIncomeMonthly;

class LineIncomeStatusHistoryController extends Controller
{
    public function index(LineIncomeMonthly $lineIncomeMonthly){
        $statuses = $lineIncomeMonthly->lineIncomeStatuses->groupBy(fn ($status) => $status->created_at->format('Y-m'));
        
        return view('admin.report.income.income-history',['statuses'=>$statuses]);
    }
}
?>