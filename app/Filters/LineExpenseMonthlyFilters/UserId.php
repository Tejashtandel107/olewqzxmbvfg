<?php

namespace App\Filters\LineExpenseMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class UserId extends QueryFilter implements FilterContract
{
    public function handle($value =""): void
    {
        if($value == "")
            return;
            
        $this->query->where("line_expenses_monthly.user_id",$value);
    }
}
