<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Line\StoreRequest;
use App\Http\Requests\Line\UpdateRequest;
use App\Http\Resources\LineResource;
use App\Models\Line;

class LineController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Client\StoreRequest  $request
     * @param  User  $client
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Line $line)
    {
        $line->operator_id = Auth::id();
        $line = $line->fill($request->all());
        $line->line_type = ($line->company->company_type)  ?? null;
        $this->assignLinePricingType($line);
        $line->account_manager_id = $line->getAccountManagerByClientId($line->client_id);

        if ($line->save()) {
            return new LineResource($line);
        } 
        else {
            return response()->json(['message' => trans('messages.server_error')], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\Client\UpdateRequest  $request
     * @param  User  $client
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Line $line)
    {
        if (!$line->register_date->isSameMonth($request->register_date) or !$line->register_date->isSameYear($request->register_date)) {
            $this->assignLinePricingType($line);
        }
        $line->fill($request->all());
        if($line->isDirty('company_id')){
            $line->line_type = ($line->company->company_type)  ?? null;
        }
        if($line->isDirty('client_id')){
            $line->account_manager_id = $line->getAccountManagerByClientId($line->client_id);
            $this->assignLinePricingType($line);
        }
        $line->updated_by = Auth::id();

        if ($line->save()) {
            return new LineResource($line);
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
    public function destroy(Request $request, Line $line)
    {
        $this->authorize('delete', $line);
    
        if ($line->delete()) 
            return response()->json([], 204);
        else 
            return response()->json(['message' => trans('messages.server_error')], 500);
    }

    private function assignLinePricingType(Line $line){
        $line->line_pricing_type = ($line->clientIncomeMonthly->pricing_type) ?? ($line->client->profile->pricing_type) ?? null;
    }
}
