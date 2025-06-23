<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Company\StoreRequest;
use App\Http\Requests\Company\UpdateRequest;
use App\Http\Resources\CompanyResource;
use App\Http\Resources\CompanyCollection;
use App\Models\Company;
use App\Models\Line;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user = Auth::user();
        $query = Company::filterBy(request()->query());

        if($user->isAccountManager()){
            $query->leftJoin('manager_client_assignments as mca', 'mca.client_id', '=', 'companies.user_id')->where('mca.account_manager_id',$user->user_id);
        }
        elseif($user->isOperator()){
            $query->leftJoin('operator_client_assignments as oca', 'oca.client_id', '=', 'companies.user_id')->where('oca.operator_id',$user->user_id);
        }
        elseif($user->isClient()){
            $query = $query->where("user_id",$user->user_id);
        }

        if ($request->boolean('show_all')) {
            $companies = $query->get();
        }
        else{
            $per_page = config('constant.PAGINATION');
            $companies = $query->paginate($per_page)->withQueryString();
        }
        return new CompanyCollection($companies);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Company\StoreRequest  $request
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Company $company)
    {
        $company->created_by = Auth::id();
        $company = $company->saveCompany($request,$company);

        if ($company) {
            return new CompanyResource($company);
        } 
        else {
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Company $company
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Company $company)
    {
        $this->authorize('view', $company);
        
        return new CompanyResource($company);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Company\UpdateRequest  $request
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Company $company)
    {
        $company = $company->saveCompany($request, $company);

        if ($company) {
            $line = Line::where('company_id',$company->company_id)
                    ->where(DB::raw('MONTH(register_date)'),"=",Carbon::now()->month)
                    ->where(DB::raw('YEAR(register_date)'),"=",Carbon::now()->year)
                    ->update([
                        'line_type'=> $request->company_type
                    ]);
            return new CompanyResource($company);
        } 
        else {
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  Company  $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Company $company)
    {
        $this->authorize('delete', $company);
  
        if($company->lines()->count() == 0){
            $return = $company->forceDelete();
        }
        else{
            $return = $company->delete();
        }

        if ($return) 
            return response()->json([], 204);
        else 
            return response()->json(['message' => trans('messages.server_error')], 500);
    }
}
