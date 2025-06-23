<?php

namespace App\Http\Controllers\Operator;

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
        $homeUrl = Helper::getUserHomeURL(Auth::user());
        $perPage = config('constant.PAGINATION');
        $data = [
            'pagetitle' => trans_choice("Task|Tasks", 2),
            "breadcrumbs" => [
                "Production Report"=>$homeUrl,
                trans_choice("Task|Tasks", 2) => "",
            ],
        ];
        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->startOfMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);
        $request->merge(["operator_id"=>Auth::id()]);
        $companies = [];
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();
        if ($request->filled('client_id')) {
            $companies = Company::where("user_id", $request->client_id)->get()->pluck('company_name', 'company_id')->toArray();
        }
        $lines = (new LineOperatorService)->getPaginatedLines($request->query(),$perPage);

            return view('operator.line.index', $data)->with(compact('lines', 'request', 'clients', 'companies'));
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
                trans_choice("Task|Tasks", 2) => route("operator.lines.index"),
                $page_title => ""
            ],
        ];
        $months = Helper::getMonths();
        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $banks[0] = "Altro";
        
        $pettyCashBookTypes = Helper::getPettyCashBookTypes();
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();

        return view('operator.line.create', array_merge($data, [
            "clients" => $clients,
            "pettyCashBookTypes" => $pettyCashBookTypes,
            "months" => $months,
            "banks" => $banks,
        ]));

        return view('operator.line.create', $data)->with(compact('clients', 'pettyCashBookTypes', 'months','banks'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Line $line)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $this->authorize('update', $line);
        
        $page_title =  'Modifica Task';

        $data = [
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice("Task|Tasks", 2) => route("operator.lines.index"),
                $page_title => ""
            ],
        ];

        $months = Helper::getMonths();
        
        $pettyCashBookTypes = Helper::getPettyCashBookTypes();
        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $banks[0] = "Altro";
        $clients = Auth::user()->assignedClients->pluck('name', 'user_id')->toArray();
        $companies = Company::withTrashed()->where('user_id', $line->client_id)->get();
        
        return view('operator.line.create', $data)->with(compact('line', 'clients', 'companies', 'pettyCashBookTypes', 'months','banks'));
    }
}
