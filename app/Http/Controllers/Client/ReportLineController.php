<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Services\LineClientService;
use Helper;

class ReportLineController extends Controller
{
    private $months;

    public function __construct(Request $request)
    {
        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->firstOfMonth()->subMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);
        
        $this->months = Helper::getMonths();                            
    }
        
    public function view(Request $request){
        $client = Auth::user();
        $home_url = Helper::getUserHomeURL($client);
        $data = [
			'pagetitle'=>"Tasks Report",
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                "Tasks Report"=>"",
            ],
        ];
        
        $request->merge(['client_id' => $client->user_id]);
        $companies = $client->companies->pluck('company_name','company_id')->toArray();

        $lineClientService = new LineClientService;

        if($request->filled('download')){
            return $lineClientService->exportClientLines($request->query());
        }
        else{
            $lineTotals = $lineClientService->getLineTotalsByClient($request->query())->groupBy(['client_id']);
            return view('client.report.line.view',array_merge($data,compact('request','lineTotals'),['months' => $this->months,'companies' => $companies]));
        }  
    }
}
?>