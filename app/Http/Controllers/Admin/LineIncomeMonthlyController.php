<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\LineIncomeMonthlyService;
use App\Models\LineIncomeMonthly;
use App\Jobs\CreateDevPOSInvoice;
use App\Facades\Helper;
use App\Exports\ClientInvoiceExport;
use Illuminate\Support\Facades\Cache;

class LineIncomeMonthlyController extends Controller
{
    public function index(Request $request){
        $request->mergeIfMissing([
            'from' => Helper::DateFormat(Carbon::now()->firstOfMonth()->subMonth(),'m/Y'),
            'to' => Helper::DateFormat(Carbon::now()->firstOfMonth()->subMonth(),'m/Y')
        ]);      
          
        $data = [
			'pagetitle'=>"Studio Production Report",
            "breadcrumbs"=>"",
        ];
        if($request->filled('account_manager_id')){
            $clients = User::find($request->account_manager_id)->assignedClients->pluck('name','user_id')->toArray();  
            $request->merge(['user_ids' => array_keys($clients)]);
        }else{
            $clients = User::client()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        }
        $account_managers = User::accountmanager()->withTrashed()->get()->pluck('name','user_id')->toArray(); 
        $lineIncomesMonthly = (new LineIncomeMonthlyService)->getAll($request->query());
        $totalLineIncomesMonthly =  (new LineIncomeMonthlyService)->getAll();

        return view('admin.report.income.client',array_merge($data,compact('request','lineIncomesMonthly','clients','account_managers','totalLineIncomesMonthly')));
    }
    public function download(Request $request)
    {
        if(isset($request->all()['id'])){
            return new ClientInvoiceExport($request->all());
        }else{
            return redirect()->back();
        }
    }

    public function show(Request $request, LineIncomeMonthly $lineIncomeMonthly){
        if(!$lineIncomeMonthly->isInvoiceReady()){
            return abort(403);
        }

        $homeUrl = Helper::getUserHomeURL(Auth::user());
        $pageTitle =  sprintf('Studio Invoice #%s',$lineIncomeMonthly->getRawOriginal('invoice_number'));
        $data = [
            'pagetitle' => $pageTitle,
            "breadcrumbs" => [
                "Production Reports"=>$homeUrl,
                trans_choice("Client|Clients", 1) => route("admin.line-incomes.index"),
                $pageTitle => ""
            ],
        ];

        if($request->download){
            return (new LineIncomeMonthlyService)->exportInvoice($lineIncomeMonthly, $request->query());
        }
        else{
            $statuses = $lineIncomeMonthly->lineIncomeStatuses->groupBy(fn ($status) => $status->created_at->format('Y-m'));
            return view('admin.invoice.line-income',$data)->with(compact('lineIncomeMonthly','statuses'));
        }
    }

    public function bulkUpdate(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:line_incomes_monthly,id',
            'status' => 'required|string|max:255',
            'total_paid' => 'required_if:status,paid|numeric|gte:0',
            'bank_fees' => 'required_if:status,paid|numeric|gte:0',
            'exchange_rate' => 'required_if:status,create-devpos-invoice|numeric',
        ]);
        if(!empty($request->exchange_rate)){
            Cache(['exchange_rate'=> $request->exchange_rate],now()->addDays(15));
        }
      
        $count= 0;
        $failure= 0;
        $errors = [];
        foreach($request->ids as $incomeMonthlyId){
            $count++;
            $lineIncomeMonthly = LineIncomeMonthly::find($incomeMonthlyId);
            $result=false;
            switch($request['status']){
                case "create-devpos-invoice":
                    $devpos = CreateDevPOSInvoice::dispatchSync($lineIncomeMonthly);
                    $result = $devpos;
                break;
                case "paid";
                    $result =  $lineIncomeMonthly->markPaid($request);                   
                break;
                case "cancel";
                    $result =  $lineIncomeMonthly->markCancel($request);                   
                break;
            } 
        
            if(!$result){
                $failure++;
                $errors[] =  $lineIncomeMonthly->user->name .' ('. $lineIncomeMonthly->pricing_date->translatedFormat("M Y") 
                . ')' . ' - ' . $lineIncomeMonthly->latestLineIncomeStatus->notes;    
            }
        }
      
        if ($failure === 0) {
            $messageType = 'success';
        }else {
            $messageType = 'danger';
        }
        return back()->withErrors($errors)->with([
            'message' => trans_choice('messages.invoice_status', $count, [
                'count' => $count, 
                'success' => $count-$failure, 
                'failure' => $failure
            ]),
            'alert_type' => $messageType
        ]);
    }
}
?>