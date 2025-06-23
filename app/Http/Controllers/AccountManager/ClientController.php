<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Country;
use App\Models\OperatorProfile;
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
            'pagetitle' => trans_choice('Client|Clients', 2),
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice('Client|Clients', 2) => "",
            ],
        ];

        $account_manager = Auth::user();
        $clients = $account_manager->assignedClients()->filterBy($request->query())->paginate($per_page)->withQueryString();

        return view('account-manager.client.index', $data)->with(compact('clients', 'request'));
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
            'pagetitle' => $page_title,
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice('Client|Clients', 2) => route("account-manager.clients.index"),
                $page_title => ""
            ],
        ];

        $operators = User::operator()->get()->pluck('name', 'user_id')->toArray();
        $country = Country::get()->pluck('country_name','country_id')->toArray();

        return view('account-manager.client.create', $data)->with(compact('operators','country'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(User $client)
    {
        $this->authorize('update', $client);
        $home_url = Helper::getUserHomeURL(Auth::user());

        $data = [
            'pagetitle' => 'Modifica Studio',
            "breadcrumbs" => [
                "Production Report"=>$home_url,
                trans_choice('Client|Clients', 2) => route("account-manager.clients.index"),
                'Modifica Studio' => ""
            ],
        ];

        $operators = User::operator()->get()->pluck('name', 'user_id')->toArray();

        $selected_operators = $client->assignedOperators->pluck('user_id')->toArray();
        $country = Country::get()->pluck('country_name','country_id')->toArray();

        return view('account-manager.client.create', $data)->with(compact('client', 'operators', 'selected_operators','country'));
    }
}
