<?php

namespace App\Filters\LineExpenseMonthlyBonusFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;

class UserId extends QueryFilter implements FilterContract
{
    public function handle($value =""): void
    {
        if($value == "")
            return;
            
        $this->query->where("line_expense_monthly_bonuses.user_id",$value);
    }
}
