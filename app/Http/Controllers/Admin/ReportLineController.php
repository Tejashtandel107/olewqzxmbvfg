<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use App\Services\LineClientService;
use App\Services\LineOperatorService;
use Helper;

class ReportLineController extends Controller
{
    private $companies;
    private $months;
    private $clients;

    public function __construct(Request $request)
    {
        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->startOfMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);

        $company_query = Company::withTrashed();
        $this->months = Helper::getMonths();                            
        $this->clients = User::client()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        if($request->filled('client_id'))
            $company_query = $company_query->where('user_id',$request->client_id); 
        
        $this->companies = $company_query->get()->pluck('company_name','company_id')->toArray();
    }

    public function showClientReport(Request $request){
        $home_url = Helper::getUserHomeURL(Auth::user());
        $data = [
			'pagetitle'=>"Studio Tasks Report",
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                "Studio Tasks Report"=>"",
            ],
        ];
        $lineClientService = new LineClientService;
        if($request->filled('download')){
            return $lineClientService->exportClientLines($request->query());
        }
        else{
            $lineTotals = $lineClientService->getLineTotalsByClient($request->query())->groupBy('client_id');
            return view('admin.report.line.client',array_merge($data,compact('request','lineTotals'),['months' => $this->months,'clients' => $this->clients, 'companies' => $this->companies]));
        }
    }

    public function showAccountManagerReport(Request $request){
        $home_url = Helper::getUserHomeURL(Auth::user());
        $data = [
			'pagetitle'=>"Team Tasks Report",
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                "Team Tasks Report"=>"",
            ],
        ];
        $request->merge(['account_manager_lines' => true]);
        $account_managers = User::accountmanager()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        $lineTotals = (new LineOperatorService)->getLineTotalsByUser($request->query())->groupBy('account_manager_id');
        
        return view('admin.report.line.team',array_merge($data,compact('request','account_managers','lineTotals'),['months' => $this->months,'clients' => $this->clients, 'companies' => $this->companies]));
    }
    
    public function showOperatorReport(Request $request){
        $home_url = Helper::getUserHomeURL(Auth::user());
        $data = [
            'pagetitle'=>"Operatore Tasks Report",
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                "Operatore Tasks Report"=>"",
            ],
        ];

        $operators = User::operator()->withTrashed()->get()->pluck('name','user_id')->toArray();
        $account_managers = User::accountmanager()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        $lineTotals = (new LineOperatorService)->getLineTotalsByUser($request->query())->groupBy('operator_id');
        
        return view('admin.report.line.operator',array_merge($data,compact('request','operators','account_managers','lineTotals'),['months' => $this->months,'clients' => $this->clients, 'companies' => $this->companies]));
    }
}
?>