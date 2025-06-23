<?php

namespace App\Filters\CompanyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class CompanyType extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where("company_type",$value);
    }
}
