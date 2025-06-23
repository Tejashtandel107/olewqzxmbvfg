<?php

namespace App\Filters\LineIncomeMonthlyFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use Illuminate\Support\Carbon;

class PricingDate extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;

        $this->query->where('line_incomes_monthly.pricing_date','=', $value);
    }
}
