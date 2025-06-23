<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Http\Requests\Operator\StoreRequest;
use App\Http\Requests\Operator\UpdateRequest;
use App\Http\Resources\OperatorResource;
use App\Http\Resources\OperatorCollection;
use App\Models\User;
use App\Models\OperatorProfile;
use App\Models\LineExpenseMonthly;

class OperatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   
        $user = Auth::user();

        if($user->isSuperAdmin()){
            $query = User::operator();
            if($request->filled('account_manager_id'))
                $query->leftJoin('manager_operator_assignments as moa', 'moa.operator_id', '=', 'users.user_id')->where('moa.account_manager_id',$request->account_manager_id);
        }
        else{
            $query = $user->assignedOperators();
        }
        $query = $query->filterBy($request->query());

        if ($request->boolean('show_all')) {
            $operators = $query->get();
        }
        else{
            $per_page = config('constant.PAGINATION');
            $operators = $query->paginate($per_page)->withQueryString();
        }
        return new OperatorCollection($operators);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\StoreRequest  $request
     * @param  User  $operator
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $operator, OperatorProfile $operator_profile)
    {
        $operator->role_id = config('constant.ROLE_OPERATOR_ID');
        $operator->created_by = Auth::id();
        $operator = $operator->saveUser($request,$operator);

        if ($operator) {
            $expense_pricing = new LineExpenseMonthly;
            $operator_profile->saveProfile($request,$operator);
            $operator->assignedAccountManagers()->sync($request->account_manager_id);
            $operator->assignedClients()->sync($request->client_id);
            $operator->load('profile');
            //Create monthly expense for the current month and year
            $operator->upsertMonthlyPricing();
            return new OperatorResource($operator);
        } 
        else {
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\UpdateRequest  $request
     * @param  User  $operator
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $operator,OperatorProfile $operator_profile)
    {
        $operator = $operator->saveUser($request, $operator);

        if ($operator) {
            $operator_profile->saveProfile($request, $operator);
            $operator->assignedAccountManagers()->sync($request->account_manager_id);
            $operator->assignedClients()->sync($request->client_id);

            if($request->filled("apply_price_change") && $request->filled("price_change_start_date") && $request->filled("price_change_end_date")){
                $operator->upsertMonthlyPricing($request->price_change_start_date,$request->price_change_end_date,true);
			}
            
            return new OperatorResource($operator);
        } 
        else {
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  User  $operator  
     * @return \Illuminate\Http\Response
     */
    public function destroy (Request $request, User $operator)
    {
        $this->authorize('delete', $operator);
        $operator->assignedAccountManagers()->detach();
        $operator->assignedClients()->detach();

        if($operator->operatorLines()->count() == 0) {
            $operator->lineExpensesMonthly()->delete();
            $operator->profile->delete();
            $return = $operator->forceDelete();
        }
        else{
            $return = $operator->delete();
        }

        if ($return) 
            return response()->json([], 204);
        else 
            return response()->json(['message' => trans('messages.server_error')], 500);
    }
}
