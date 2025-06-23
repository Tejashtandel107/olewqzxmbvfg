<?php

namespace App\Filters\LineIncomeMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class Id extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        if(empty($value))
            return;

        if(is_array($value)){
            $this->query->whereIn("line_incomes_monthly.id",$value);
        }else{
            $this->query->where("line_incomes_monthly.id",$value);
        }
    }
}
