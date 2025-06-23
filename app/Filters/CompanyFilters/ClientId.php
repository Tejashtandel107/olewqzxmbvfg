<?php

namespace App\Filters\CompanyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class ClientId extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where("companies.user_id",$value);
    }
}
