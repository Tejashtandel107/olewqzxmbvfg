<?php

namespace App\Filters\LineExpenseMonthlyBonusFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use Illuminate\Support\Carbon;

class PricingDate extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;

        $this->query->where('line_expense_monthly_bonuses.pricing_date','=', $value);
    }
}
