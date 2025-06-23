<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Helper;

class OperatorController extends Controller
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
            'pagetitle'=>trans_choice('Operator|Operators',2),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Operator|Operators',2)=>"",
            ],
        ];

        $operators = User::operator()
                        ->leftJoin('operator_profiles', 'operator_profiles.id', '=', 'users.profile_id')
                        ->filterBy($request->query())
                        ->paginate($per_page)->withQueryString();
        return view('admin.operator.index',$data)->with(compact('operators','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare operatore';

        $data = [
			'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Reports"=>$home_url,
                trans_choice('Operator|Operators',2)=>route("admin.operators.index"),
                $page_title=>""
            ],
        ];
    
        $pricing_types = Helper::getOperatorPricingTypes();
        $account_managers = User::accountmanager()->get()->pluck('name','user_id')->toArray(); 
        $clients = User::client()->get()->pluck('name','user_id')->toArray(); 
                
        return view('admin.operator.create',$data)->with(compact('account_managers','clients','pricing_types'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $operator)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $data = [
			'pagetitle'=>__('Edit Operator'),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Operator|Operators',2)=>route("admin.operators.index"),
                __('Edit Operator')=>""
            ],
        ];
        
        $pricing_types = Helper::getOperatorPricingTypes();
        $account_managers = User::accountmanager()->get()->pluck('name','user_id')->toArray(); 
        $clients = User::client()->get()->pluck('name','user_id')->toArray(); 
        $selected_clients = $operator->assignedClients->pluck('user_id')->toArray();
        $selected_managers = $operator->assignedAccountManagers->pluck('user_id')->toArray();

        return view('admin.operator.create',$data)->with(compact('operator','account_managers','clients','pricing_types','selected_managers','selected_clients'));
    }
    
}
