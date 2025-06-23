<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Helper;

class AccountManagerController extends Controller
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
            'pagetitle'=>trans_choice('Account Manager|Account Managers',2),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Account Manager|Account Managers',2)=>"",
            ],
        ];
        
        $account_managers = User::accountmanager()
                            ->leftJoin('account_manager_profiles', 'account_manager_profiles.id', '=', 'users.profile_id')
                            ->filterBy($request->query())
                            ->paginate($per_page)->withQueryString();

        return view('admin.account-manager.index',$data)->with(compact('account_managers','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()            
    { 
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare Account Manager';

        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Account Manager|Account Managers',2)=>route("admin.account-managers.index"),
                $page_title=>""
            ],
        ];
        
        $pricing_types = Helper::getAccountManagerPricingTypes();
        $operators = User::operator()->get()->pluck('name','user_id')->toArray(); 
        $clients = User::client()->get()->pluck('name','user_id')->toArray(); 

        return view('admin.account-manager.create',$data)->with(compact('operators','clients','pricing_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $account_manager)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Modifica Account Manager';
        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Account Manager|Account Managers',2)=>route("admin.account-managers.index"),
                $page_title=>""
            ],
        ];
        
        $pricing_types = Helper::getAccountManagerPricingTypes();
        $operators = User::operator()->get()->pluck('name','user_id')->toArray();         
        $clients = User::client()->get()->pluck('name','user_id')->toArray(); 
        $selected_operators = $account_manager->assignedOperators->pluck('user_id')->toArray();
        $selected_clients = $account_manager->assignedClients;
        $primary_selected_clients = $selected_clients->where("is_primary",1);
        $secondary_selected_clients = $selected_clients->where("is_primary",0);

        return view('admin.account-manager.create',$data)->with(compact('account_manager','operators','clients','pricing_types','selected_operators','secondary_selected_clients','primary_selected_clients'));
    }
}
