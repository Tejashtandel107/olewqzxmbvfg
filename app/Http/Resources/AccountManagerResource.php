<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $response = [
            "userId"    => $this->user_id,
            "name"      => $this->name,
            "email"     => $this->email,
            "status"    => $this->status,
            "photo"     => $this->photo,
        ];
        
        if ($request->query('includeClients')) {
            $response['clients'] = ClientResource::collection($this->assignedClients);
        }

        if($request->user()->isSuperAdmin()){
            $response["pricingType"]        = $this->profile->pricing_type;
            $response["price"]              = $this->profile->price;
            $response["priceRigheOrdinaria"]     = $this->profile->price_righe_ordinaria;
            $response["priceRigheSemplificata"]  = $this->profile->price_righe_semplificata;
            $response["priceRigheCorrispettiviSemplificata"]     = $this->profile->price_righe_corrispettivi_semplificata;
            $response["priceRighePagheSemplificata"]  = $this->profile->price_righe_paghe_semplificata;
            $response["bonusTarget"]        = $this->profile->bonus_target;
        }
        return $response;
    }
}
