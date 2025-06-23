<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Resources\ClientResource;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\ClientProfile;

class ClientController extends Controller
{
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  App\Http\Requests\Client\StoreRequest  $request
	 * @param  User  $client
	 * @return \Illuminate\Http\Response
	 */
	public function store(StoreRequest $request, User $client, ClientProfile $client_profile)
	{
		$client->role_id = config('constant.ROLE_CLIENT_ID');
		$client->created_by = Auth::id();
		$client = $client->saveUser($request, $client);

		if ($client) {
			$client_profile->saveProfile($request,$client);
			$client->assignedAccountManagers()->sync($this->formatAssignedAccountManagers($request));
			$client->assignedOperators()->sync($request->operator_id);
			$client->load('profile');
			//Create monthly income for the current month and year
			$client->upsertMonthlyPricing();
			return new ClientResource($client);
		} 
		else {
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
	public function show(Request $request, User $client)
	{
		$this->authorize('view', $client);

		if ($request->query('includeCompanies')) {
			$client->load('companies');
		}

		return new ClientResource($client);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  App\Http\Requests\Client\UpdateRequest  $request
	 * @param  User  $client
	 * @return \Illuminate\Http\Response
	 */
	public function update(UpdateRequest $request, User $client, ClientProfile $client_profile)
	{
		$client = $client->saveUser($request, $client);

		if ($client) {
			$client_profile->saveProfile($request,$client);
			if(Auth::user()->isSuperAdmin()){
				$client->assignedAccountManagers()->sync($this->formatAssignedAccountManagers($request));
			}
			$client->assignedOperators()->sync($request->operator_id);
			
            if($request->filled("apply_price_change") && $request->filled("price_change_start_date") && $request->filled("price_change_end_date")){
				$client->upsertMonthlyPricing($request->price_change_start_date,$request->price_change_end_date,true);
			}

			return new ClientResource($client);
		} 
		else {
			return response()->json(['message' => trans('messages.server_error')], 500);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Illuminate\Http\Request  $request
	 * @param  User  $client
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Request $request, User $client)
	{
		$this->authorize('delete', $client);
		$client->assignedAccountManagers()->detach();
		$client->assignedOperators()->detach();

		if ($client->clientLines()->count() == 0) {
			$client->lineIncomesMonthly()->delete();
			$client->companies()->forceDelete();
			$client->profile->delete();
			$return = $client->forceDelete();
		}
		else
			$return = $client->delete();
		
		if ($return)
			return response()->json([], 204);
		else
			return response()->json(['message' => trans('messages.server_error')], 500);
	}
	private function formatAssignedAccountManagers(Request $request){
		$assignedManagers = []; 

		if ($request->filled('primary_account_manager_id')) {	
			$assignedManagers[$request->primary_account_manager_id] = ['is_primary' => 1]; 
		}
		if ($request->filled('secondary_account_manager_id')) {	
			foreach ($request->secondary_account_manager_id as $accountManagerId) {
				if (!array_key_exists($accountManagerId, $assignedManagers)) {
					$assignedManagers[$accountManagerId] = ['is_primary' => 0];
				}
			} 
		}
		return $assignedManagers;
    }
}
