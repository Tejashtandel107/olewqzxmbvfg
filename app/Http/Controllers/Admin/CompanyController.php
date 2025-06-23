<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\User;
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
        $home_url = Helper::getUserHomeURL(Auth::user());
        $per_page = config('constant.PAGINATION');
        $data = [
			'pagetitle'=>trans_choice('Company|Companies',2),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Company|Companies',2)=>"",
            ],
        ];
        
        $companies = Company::with([
            'client' => function ($query) {
                $query->withTrashed();
            }
        ])
        ->filterBy(request()->query())
        ->orderBy('company_name','asc')
        ->paginate($per_page)
        ->withQueryString();

        $clients = User::client()->get()->pluck('name','user_id')->toArray(); 
        
        return view('admin.company.index',$data)->with(compact('clients','companies','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare azienda';

        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Company|Companies',2)=>route("admin.companies.index"),
                $page_title=>""
            ],
        ];

        $company_types  = Helper::getCompanyTypes();
        $business_types = Helper::getBusinessTypes();
        $clients        = User::client()->get()->pluck('name','user_id')->toArray(); 

        return view('admin.company.create',$data)->with(compact('clients','company_types','business_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Company $company)
    {   
        $home_url = Helper::getUserHomeURL(Auth::user());   
        $data = [
            'pagetitle'=>__('Edit Company'),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Company|Companies',2)=>route("admin.companies.index"),
                __('Edit Company')=>""
            ],
        ];
        
        $company_types  = Helper::getCompanyTypes();
        $business_types = Helper::getBusinessTypes();
        $clients        = User::client()->withTrashed()->get()->pluck('name','user_id')->toArray(); 

        return view('admin.company.create',$data)->with(compact('company','clients','company_types','business_types'));
    }
}
