<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LineIncomeMonthlyService;
use Helper;

class LineIncomeMonthlyController extends Controller
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
			'pagetitle'=>"Studio Production Report",
            "breadcrumbs"=>"",
        ];
        $clients = Auth::user()->assignedClients->pluck('name','user_id')->toArray();
        if($request->filled('user_id') && array_key_exists($request->user_id,$clients)){
            $request->merge(['user_id' => $request->user_id]);
        }
        else{
            $request->merge(['user_ids' => array_keys($clients)]);
        }
        $lineIncomesMonthly = (new LineIncomeMonthlyService)->getAll($request->query());
       
        return view('account-manager.report.income.client',array_merge($data,compact('request','lineIncomesMonthly','clients')));
    }
}
?>