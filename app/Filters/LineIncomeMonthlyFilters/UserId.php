<?php

namespace App\Filters\LineIncomeMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class UserId extends QueryFilter implements FilterContract
{
    public function handle($value): void
    {
        if(empty($value))
            return;
            
        $this->query->where("line_incomes_monthly.user_id",$value);
    }
}
