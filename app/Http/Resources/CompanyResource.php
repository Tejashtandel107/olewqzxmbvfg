<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
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
            "companyId" => $this->company_id,
            "userId" => $this->user_id,
            "companyName" => $this->company_name,
            "companyType" => $this->company_type,
            "businessType" => $this->business_type,
            "vatTax" => $this->vat_tax,
        ];
    }
}
