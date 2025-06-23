<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Company;
use Helper;

class CompanyController extends Controller
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
        $per_page = config('constant.PAGINATION');
        $data = [
			'pagetitle'=>trans_choice('Company|Companies',2),
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                trans_choice('Company|Companies',2)=>"",
            ],
        ];
        $companies = Company::select('companies.*','users.*')
                ->leftJoin('users', 'users.user_id', '=', 'companies.user_id')
                ->leftJoin('manager_client_assignments as mca', 'mca.client_id', '=', 'companies.user_id')
                ->filterBy(request()->query())
                ->where('mca.account_manager_id',$account_manager->user_id)
                ->paginate($per_page)
                ->withQueryString();
        
        $clients = Auth::user()->assignedClients->pluck('name','user_id')->toArray();
        
        return view('account-manager.company.index',$data)->with(compact('clients','companies','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create',Company::class);
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare azienda';

        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                trans_choice('Company|Companies',2)=>route("account-manager.companies.index"),
                $page_title=>""
            ],
        ];
        $company_types = Helper::getCompanyTypes();
        $business_types = Helper::getBusinessTypes();
        $clients = Auth::user()->assignedClients->pluck('name','user_id')->toArray();

        return view('account-manager.company.create',$data)->with(compact('clients','company_types','business_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {   
        $this->authorize('update', $company);   
        $home_url = Helper::getUserHomeURL(Auth::user());
        
        $data = [
            'pagetitle'=>__('Edit Company'),
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                trans_choice('Company|Companies',2)=>route("account-manager.companies.index"),
                __('Edit Company')=>""
            ],
        ];

        $company_types = Helper::getCompanyTypes();
        $business_types = Helper::getBusinessTypes();
        $clients = Auth::user()->assignedClients->pluck('name','user_id')->toArray();
        
        return view('account-manager.company.create',$data)->with(compact('company','clients','company_types','business_types'));
    }
}
