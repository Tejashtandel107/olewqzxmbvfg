<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Models\User;
use App\Models\Setting;
use App\Models\Country;
use App\Models\OperatorProfile;
use App\Models\AccountManagerProfile;
use App\Models\ClientPriceLevel;
use Helper;

class ClientController extends Controller
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
			'pagetitle'=>trans_choice('Client|Clients',2),
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Client|Clients',2)=>"",
            ],
        ];

        $clients = User::client()
                    ->leftJoin('client_profiles', 'client_profiles.id', '=', 'users.profile_id')
                    ->filterBy($request->query())
                    ->paginate($per_page)->withQueryString();

        return view('admin.client.index',$data)->with(compact('clients','request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Creare Studio';

        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Client|Clients',2)=>route("admin.clients.index"),
                $page_title=>""
            ],
        ];

        $pricing_types = Helper::getStudioPricingTypes();
        $operators = User::operator()->get()->pluck('name','user_id')->toArray();
        $priceLevels = ClientPriceLevel::get()->pluck('title','price_level_id')->toArray();
        $account_managers = User::accountmanager()->get()->pluck('name','user_id')->toArray();
        $country = Country::get()->pluck('country_name','country_id')->toArray();
        
        return view('admin.client.create',$data)->with(compact('operators','account_managers','pricing_types','priceLevels','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(User $client)
    {
        $home_url = Helper::getUserHomeURL(Auth::user());
        $page_title =  'Modifica Studio';

        $data = [
            'pagetitle'=>$page_title,
            "breadcrumbs"=>[
                "Production Reports"=>$home_url,
                trans_choice('Client|Clients',2)=>route("admin.clients.index"),
                $page_title=>""
            ],
        ];

        $pricing_types = Helper::getStudioPricingTypes();
        
        $operators = User::operator()->get()->pluck('name','user_id')->toArray();
        $account_managers = User::accountmanager()->get()->pluck('name','user_id')->toArray();
        $selected_managers = $client->assignedAccountManagers;
        $primary_selected_manager = $selected_managers->where("is_primary",1);
        $secondary_selected_managers = $selected_managers->where("is_primary",0);
        $selected_operators = $client->assignedOperators->pluck('user_id')->toArray();
        $priceLevels = ClientPriceLevel::get()->pluck('title','price_level_id')->toArray();
        $country = Country::get()->pluck('country_name','country_id')->toArray();
        
        return view('admin.client.create',$data)->with(compact('client','operators','account_managers','secondary_selected_managers','primary_selected_manager','selected_operators','pricing_types','priceLevels','country'));
    }
}
