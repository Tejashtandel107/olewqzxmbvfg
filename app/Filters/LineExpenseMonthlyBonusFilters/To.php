<?php

namespace App\Filters\LineExpenseMonthlyBonusFilters;

use App\Filters\QueryFilter;
use App\Filters\FilterContract;
use Illuminate\Support\Carbon;

class To extends QueryFilter implements FilterContract
{
    public function handle($value = ""): void
    {
        if($value == "")
            return;

        $dt = Carbon::createFromFormat('d/m/Y', "01/" . $value);
        $this->query->where('line_expense_monthly_bonuses.pricing_date','<=', $dt->format('Y-m-01'));
    }
}
