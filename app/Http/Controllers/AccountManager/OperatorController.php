<?php

namespace App\Http\Controllers\AccountManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
                "Production Report"=>$home_url,
                trans_choice('Operator|Operators',2)=>"",
            ],
        ];

        $operators = Auth::user()->assignedOperators()->filterBy($request->query())->paginate($per_page)->withQueryString();
        return view('account-manager.operator.index',$data)->with(compact('operators','request'));
    }
}
