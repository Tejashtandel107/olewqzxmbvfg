<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use App\Services\LineOperatorService;
use Helper;

class ReportLineController extends Controller
{
    private $months;

    public function __construct(Request $request)
    {
        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->startOfMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);
        
        $this->months = Helper::getMonths();                            
    }
    
    public function view(Request $request){
        $operator = Auth::user();
        $home_url = Helper::getUserHomeURL($operator);
        $data = [
            'pagetitle'=>"Tasks Report",
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                "Tasks Report"=>"",
            ],
        ];
        
        $request->merge(['operator_id' => $operator->user_id]);
        
        $clients = $operator->assignedClients->pluck('name','user_id')->toArray();
        $companies = $this->getCompanies($operator,$request)->pluck('company_name','company_id')->toArray();
        
        $lineOperatorService = new LineOperatorService;
        $lines = $lineOperatorService->getAllLines($request->query());
        $lineTotals = $lineOperatorService->getLineTotalsByUser($request->query())->groupBy('operator_id');
        
        return view('operator.report.line.view',array_merge($data,compact('request','lines','lineTotals'),['months' => $this->months,'clients' => $clients, 'companies' => $companies]));
    }

    private function getCompanies($user,$request){
        $query = Company::withTrashed()
                        ->leftJoin('operator_client_assignments as oca', 'oca.client_id', '=', 'companies.user_id')
                        ->where('oca.operator_id',$user->user_id);
        
        if($request->filled('client_id')){
            $query = $query->where('user_id',$request->client_id); 
        }
        return $query->get();            
    }
}
?>