<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AccountManager\StoreRequest;
use App\Http\Requests\AccountManager\UpdateRequest;
use App\Http\Resources\AccountManagerResource;
use App\Models\User;
use App\Models\AccountManagerProfile;

class AccountManagerController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\AccountManager\StoreRequest  $request
     * @param  User  $account_manager
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $account_manager, AccountManagerProfile $account_manager_profile)
    {   
        $account_manager->role_id = config('constant.ROLE_ACCOUNT_MANAGER_ID');
        $account_manager->created_by = Auth::id();
        $account_manager = $account_manager->saveUser($request,$account_manager);
        
        if($account_manager){
            $account_manager_profile->saveProfile($request,$account_manager);
            $account_manager->assignedClients()->sync($this->formatAssignedClients($request));
            $account_manager->assignedOperators()->sync($request->operator_id);
            $account_manager->load('profile');
			//Create monthly expense for the current month and year
            $account_manager->upsertMonthlyPricing();
            return new AccountManagerResource($account_manager);
        }
        else{
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }
	/**
	 * Display the specified resource.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  User $client
	 * @return \Illuminate\Http\Response
	 */
	public function show(Request $request, User $accountManager)
	{
		$this->authorize('view', $accountManager);

		return new AccountManagerResource($accountManager);
	}
  
    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\AccountManager\UpdateRequest  $request
     * @param  User  $account_manager
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $account_manager, AccountManagerProfile $account_manager_profile)
    {
        $request->merge(['role_id' => config('constant.ROLE_ACCOUNT_MANAGER_ID')]);//add role id to request object

        $account_manager = $account_manager->saveUser($request,$account_manager);

        if($account_manager){
            $account_manager_profile->saveProfile($request,$account_manager);
            $account_manager->assignedClients()->sync($this->formatAssignedClients($request));
            $account_manager->assignedOperators()->sync($request->operator_id);

            if($request->filled("apply_price_change") && $request->filled("price_change_start_date") && $request->filled("price_change_end_date")){
                $account_manager->upsertMonthlyPricing($request->price_change_start_date,$request->price_change_end_date,true);
			}

            return new AccountManagerResource($account_manager);
        }
        else{
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  User  $account_manager
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,User $account_manager)
    {
        $this->authorize('delete', $account_manager);
        $account_manager->assignedOperators()->detach();
        $account_manager->assignedClients()->detach();
    
        if($account_manager->accountManagerLines()->count() == 0) {
            $account_manager->lineExpensesMonthly()->delete();
            $account_manager->profile->delete();
            $return = $account_manager->forceDelete();
        }
        else
            $return = $account_manager->delete();

        if ($return) 
            return response()->json([], 204);
        else 
            return response()->json(['message' => trans('messages.server_error')], 500);
    }

    private function formatAssignedClients(Request $request){
        $clients = [];
        if ($request->filled('primary_client_id')) {	
            foreach ($request->primary_client_id as $id) {
                $clients[$id] = ['is_primary' => 1]; 
            }
        }
        if ($request->filled('secondary_client_id')) {	
            foreach ($request->secondary_client_id as $id) {
                if (!array_key_exists($id, $clients)) {
                    $clients[$id] = ['is_primary' => 0];
                }
            }
        }
        return $clients;
    }
}
