<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PricingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "pricingId" => $this->pricing_id,
            "userId" => $this->user_id,
            "companyId" => $this->company_id,
            "pricingType" => $this->pricing_type,
            "price" => $this->price,
            'priceOrdinaria ' => $this->price_ordinaria ,
            'priceSemplificata'=>$this->price_semplificata,
            'milestone'=>$this->milestone,
            'monthId'=>$this->month_id,
            'year'=>$this->year,
        ];
    }
}
