<?php

namespace App\Filters\CompanyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class Search extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;
            
        $this->query->where(function ($q) use($value) { //  use function to pass data inside
            $q->orWhere('company_name', 'LIKE','%' . $value . '%')->orWhere('company_type', 'LIKE','%' . $value . '%')->orWhere('vat_tax', 'LIKE','%' . $value . '%')->orWhere('business_type', 'LIKE','%' . $value . '%');
        });
    }
}
