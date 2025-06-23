<?php

namespace App\Filters\LineIncomeMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class UserIds extends QueryFilter implements FilterContract
{
    public function handle($value=array()): void
    {
        if(empty($value))
            return;
            
        if(is_array($value)){
            $this->query->whereIn("line_incomes_monthly.user_id",$value);
        }
    }
}
