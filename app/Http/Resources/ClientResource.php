<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\CompanyResource;

class ClientResource extends JsonResource
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
            'companies' => CompanyResource::collection($this->whenLoaded('companies')),
        ];

        if($request->user()->isSuperAdmin()){
            $response["pricingType"]        = $this->profile->pricing_type;
            $response["price"]              = $this->profile->price;
            $response["priceOrdinaria"]     = $this->profile->price_ordinaria;
            $response["priceSemplificata"]  = $this->profile->price_semplificata;
            $response["milestone"]          = $this->profile->milestone;
        }
        return $response;
    }
}
