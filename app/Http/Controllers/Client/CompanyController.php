<?php
namespace App\Http\Controllers\Client;

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
        $home_url = Helper::getUserHomeURL(Auth::user());
        $per_page = config('constant.PAGINATION');
        $data = [
			'pagetitle'=>trans_choice('Company|Companies',2),
            "breadcrumbs"=>[
                "Production Report"=>$home_url,
                trans_choice('Company|Companies',2)=>"",
            ],
        ];
        $companies = Company::filterBy(request()->query())
                ->where("user_id",Auth::id())
                ->paginate($per_page)
                ->withQueryString();
        
        return view('client.company.index',$data)->with(compact('companies','request'));
    }
}
