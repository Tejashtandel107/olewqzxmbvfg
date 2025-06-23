<?php

namespace App\Http\Controllers\AccountManager;

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
        $accountManager = Auth::user();
        $home_url = Helper::getUserHomeURL($accountManager);
        $data = [
            'pagetitle'=>"Operatore Tasks Report",
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                "Operatore Tasks Report"=>"",
            ],
        ];
        
        $operatorId = ($request->filled('operator_id')) ? $request->input('operator_id') : 0;
        $clients = $accountManager->assignedClients()->withTrashed()->pluck('name','user_id')->toArray();
        $companies = $this->getCompanies($accountManager,$request)->pluck('company_name','company_id')->toArray();
        $operators = $accountManager->assignedOperators()->withTrashed()->get()->pluck('name','user_id')->toArray();
        
        if ($operatorId > 0 && (array_key_exists($operatorId, $operators) or $operatorId==$accountManager->user_id)) {
            $request->merge(['operator_id' => $operatorId]);
        }
        else{
            $request->merge(['operator_id' => null]);
            $request->merge(['account_manager_id' => $accountManager->user_id]);
        }        
        $lineOperatorService = new LineOperatorService;
        $lines = $lineOperatorService->getAllLines($request->query());
        $lineTotals = $lineOperatorService->getLineTotalsByUser($request->query())->groupBy('operator_id');
        
        return view('account-manager.report.line.view',array_merge($data,compact('request','lines','operators','accountManager','lineTotals'),['months' => $this->months,'clients' => $clients, 'companies' => $companies]));
    }

    private function getCompanies($user,$request){
        $company_query = Company::withTrashed()
                        ->leftJoin('manager_client_assignments as mca', 'mca.client_id', '=', 'companies.user_id')->where('mca.account_manager_id',$user->user_id);

        if($request->filled('client_id'))
            $company_query = $company_query->where('user_id',$request->client_id); 

        return $company_query->get();            
    }
}
?>