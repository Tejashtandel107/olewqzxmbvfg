<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\LineIncomeMonthlyService;
use App\Models\LineIncomeMonthly;
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
			'pagetitle'=>"Production Report",
            "breadcrumbs"=>"",
        ];
        $client = Auth::user();
        $request->merge(['user_id' => $client->user_id]);
        $lineIncomesMonthly = (new LineIncomeMonthlyService)->getAll($request->query());

        return view('client.report.income.view',array_merge($data,compact('request','lineIncomesMonthly')));
    }
    public function show(Request $request,LineIncomeMonthly $lineIncomeMonthly){
        $request->merge([
            'download' => 1
        ]);      

        if($request->download){
            return (new LineIncomeMonthlyService)->exportInvoice($lineIncomeMonthly, $request->query());
        }
    }
}
?>