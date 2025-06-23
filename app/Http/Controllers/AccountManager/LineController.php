<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Line;
use App\Models\Bank;
use App\Models\Company;
use App\Services\LineOperatorService;
use Helper;

class LineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $account_manager = Auth::user();
        $home_url = Helper::getUserHomeURL($account_manager);
        $perPage = config('constant.PAGINATION');
        $data = [
            'pagetitle' => trans_choice("Task|Tasks", 2),
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice("Task|Tasks", 2) => "",
            ],
        ];

        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->startOfMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);
        
        $companies = [];
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();

        if ($request->filled('client_id')) {
            $companies = Company::where("user_id", $request->client_id)->get()->pluck('company_name','company_id')->toArray();
        }
        $request->merge(["account_manager_id"=>$account_manager->user_id]);

        $lines = (new LineOperatorService)->getPaginatedLines($request->query(),$perPage);

        return view('account-manager.line.index', $data)->with(compact('lines', 'request', 'clients', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare Task';

        $data = [
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice("Task|Tasks", 2) => route("account-manager.lines.index"),
                $page_title => ""
            ],
        ];
        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();
        $pettyCashBookTypes = Helper::getPettyCashBookTypes();
        $months = Helper::getMonths();

        return view('account-manager.line.create', $data)->with(compact('clients', 'pettyCashBookTypes', 'months','banks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Line $line)
    {
        $this->authorize('update', $line);
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Modifica Task';

        $data = [
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice("Task|Task", 2) => route("account-manager.lines.index"),
                $page_title => ""
            ],
        ];
        $months = Helper::getMonths();
        $pettyCashBookTypes = Helper::getPettyCashBookTypes();
        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();
        $companies = Company::withTrashed()->where('user_id', $line->client_id)->get();

        return view('account-manager.line.create', $data)->with(compact('line', 'clients', 'companies', 'pettyCashBookTypes', 'months','banks'));
    }
}
