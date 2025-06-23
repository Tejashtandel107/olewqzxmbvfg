<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Line;
use App\Models\User;
use App\Models\Bank;
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
        $home_url = Helper::getUserHomeURL(Auth::user());
        $perPage = config('constant.PAGINATION');
        $companies = [];

        $request->mergeIfMissing([
            'from_date' => Helper::DateFormat(Carbon::now()->startOfMonth(),config('constant.DATE_FORMAT')),
            'to_date' => Helper::DateFormat(Carbon::today(),config('constant.DATE_FORMAT'))
        ]);

        $data = [
            'pagetitle' => trans_choice("Task|Tasks", 2),
            "breadcrumbs" => [
                "Production Reports"=>$home_url,
                trans_choice("Task|Tasks", 2) => "",
            ],
        ];

        $clients = User::client()->get()->pluck('name', 'user_id')->toArray();
        $operators = User::operator()->get()->pluck('name', 'user_id')->toArray();
        $account_managers = User::accountmanager()->get()->pluck('name', 'user_id')->toArray();

        if ($request->filled('client_id'))
            $companies = Company::where('user_id', $request->client_id)->get()->pluck('company_name', 'company_id')->toArray();
            
        $lines = (new LineOperatorService)->getPaginatedLines($request->query(),$perPage);
        
        return view('admin.line.index', $data)->with(compact('lines', 'request', 'clients', 'operators', 'account_managers', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  __('Create') . " " . trans_choice("Task|Tasks", 1);

        $data = [
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Reports"=>$home_url,
                trans_choice("Task|Tasks", 2) => route("admin.lines.index"),
                $page_title => ""
            ],
        ];

        $pettyCashBookTypes = Helper::getPettyCashBookTypes();

        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $banks[0] = "Altro";
        $months = Helper::getMonths();
        $clients = User::client()->get()->pluck('name', 'user_id')->toArray();

        return view('admin.line.create', $data)->with(compact('clients','pettyCashBookTypes', 'months','banks'));
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
        $page_title =  'Modifica Task';

        $data = [
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Reports"=>$home_url,
                trans_choice("Task|Tasks", 2) => route("admin.lines.index"),
                $page_title => ""
            ],
        ];
        
        $pettyCashBookTypes = Helper::getPettyCashBookTypes();

        $banks = Bank::get()->pluck('name', 'bank_id')->toArray();
        $banks[0] = "Altro";
        $months = Helper::getMonths();
        $clients = User::client()->withTrashed()->get()->pluck('name', 'user_id')->toArray();
        $companies = Company::withTrashed()->where('user_id', $line->client_id)->get();

        return view('admin.line.create', $data)->with(compact('line', 'clients', 'companies', 'pettyCashBookTypes', 'months','banks'));
    }
}
